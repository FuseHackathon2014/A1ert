import sun.reflect.generics.reflectiveObjects.NotImplementedException;

import java.util.ArrayList;

/**
 * Created by pc on 11/14/2014.
 */
public class ParseEngine
{
    private ArrayList<Definition> _definitions = new ArrayList<Definition>();
    private ArrayList<MeaningfulWord> _words = new ArrayList<MeaningfulWord>();

    public ParseEngine(ArrayList<MeaningfulWord> words, ArrayList<Definition> definitions)
    {
        this._definitions = definitions;
        this._words = words;
    }

    public void parseSentence(ArrayList<Token> tokenList, int nest)
    {
        ArrayList<Token> operationalTokenList = (ArrayList<Token>)tokenList.clone();
        ArrayList<Token> newTokenList = new ArrayList<Token>();

        if(nest == 0)
        {
            operationalTokenList.add(new Token("", "WALL")); //end wall
        }

        //System.out.println("Preprocessed: ");

        for(int i = 0; i < nest; i ++)
        {
            //System.out.print("\t");
        }

        for(int i = 0; i < operationalTokenList.size(); i ++)
        {
            //System.out.print("\t" + operationalTokenList.get(i)._classification + " ");
        }
        //System.out.println();

        MAIN: for(int length = operationalTokenList.size(); length > 0; length --)
        {
            for (int offset = 0; offset <= operationalTokenList.size() - length; offset++)
            {
                Definition matchingDefinition = null;
                Rule matchingRule = null;

                for (int defIndex = 0; defIndex < _definitions.size(); defIndex++)
                {
                    if ((matchingRule
                            = findMatchingRule(new ArrayList<Token>(operationalTokenList.subList(offset, length + offset)),
                            _definitions.get(defIndex))) != null)
                    {
                        matchingDefinition = _definitions.get(defIndex);
                        break;
                    }
                }

                if (matchingDefinition != null
                        && matchingRule != null)
                {
                    //the recursion begins...
                    ArrayList<Token> recursiveSet = (ArrayList<Token>) operationalTokenList.clone();
                    DefinitionPayload payload = new DefinitionPayload(matchingDefinition);
                    payload.addToken(recursiveSet.get(offset));

                    recursiveSet.set(offset, payload);

                    for (int i = 1; i < matchingRule.length(); i++)
                    {
                        payload.addToken(recursiveSet.get(offset + i));
                        recursiveSet.set(offset + i, null);
                    }

                    for(int nullRemove = 0; nullRemove < recursiveSet.size(); nullRemove ++)
                    {
                        if(recursiveSet.get(nullRemove) == null)
                        {
                            recursiveSet.remove(nullRemove);
                            nullRemove --;
                        }
                    }

                    parseSentence(recursiveSet, nest + 1); //final result on recursive set

                    if(isInAFinalState(recursiveSet))
                    {
                        operationalTokenList = recursiveSet;
                        break MAIN;
                    }
                }
            }
        }

       // System.out.println("Postprocessed: ");
        for(int i = 0; i < nest; i ++)
        {
            //System.out.print("\t");
        }

        for(int i = 0; i < operationalTokenList.size(); i ++)
        {
            if(operationalTokenList.get(i) != null)
            {
                //System.out.print("\t" + operationalTokenList.get(i)._classification + " ");
                newTokenList.add(operationalTokenList.get(i));
            }
        }
        //System.out.println();

        tokenList.clear();
        for(Token token : operationalTokenList)
        {
            tokenList.add(token);
        }
    }

    public int getConnotation(ArrayList<Token> words)
    {
        int[] arr = new int[words.size()];

        for(int i = 0; i < words.size(); i ++)
        {
            Token token = words.get(i);

            if(token instanceof MeaningfulWord)
            {
                MeaningfulWord word = (MeaningfulWord) token;
                arr[i] = word.getPositiveness();
            }
            else if(token instanceof DefinitionPayload)
            {
                arr[i] = getConnotation(((DefinitionPayload) token).payload);
            }
        }

        return weirdMath(arr);
    }

    public String getSubject(ArrayList<Token> words)
    {
        Token token = words.get(0);

        if(token instanceof DefinitionPayload) //assume this is the NP
        {
        }

        throw new NotImplementedException();
    }

    private int weirdMath(int[] arr)
    {
        int temp = -2; //[-1, 1]

        if(arr.length == 1) return arr[0]; //handle singular cases

        for(int i = 0; i < arr.length; i ++)
        {
            if(temp == -2 && i < arr.length - 1) temp = arr[i++];

            if(temp == 1)
            {
                if(arr[i] == 1)
                {
                    temp = 1;
                }
                else if(arr[i] == 0)
                {
                    temp = 1;
                }
                else
                {
                    temp = -1;
                }
            }
            else if(temp == 0)
            {
                if(arr[i] == 1)
                {
                    temp = 1;
                }
                else if(arr[i] == 0)
                {
                    temp = 0;
                }
                else
                {
                    temp = -1;
                }
            }
            else
            {
                if(arr[i] == 1)
                {
                    temp = -1;
                }
                else if(arr[i] == 0)
                {
                    temp = -1;
                }
                else
                {
                    temp = 1;
                }
            }
        }

        return temp;
    }

    private Rule findMatchingRule(ArrayList<Token> subset, Definition definition)
    {
        Rule matchingRule = null;

        for(int index = 0; index < definition.length(); index ++)
        {
            if(isMatchingRule(subset, definition.getRule(index)))
            {
                matchingRule = definition.getRule(index);
                break;
            }
        }

        return matchingRule;
    }

    private boolean isInAFinalState(ArrayList<Token> tokenList)
    {
        /*
        boolean result = true;

        for(int i = 0; i < tokenList.size(); i ++)
        {
            if(tokenList.get(i) instanceof Definition)
            {
                Definition def = (Definition) tokenList.get(i);

                if(!def.isEndState())
                {
                    result = false;
                    break;
                }
            }
            else
            {
                result = false;
                break;
            }
        }
        */
        return (tokenList.size() == 1 || tokenList.size() == 2 && tokenList.get(1)._classification.equals("WALL"))
                && (tokenList.get(0) instanceof Definition)
                && ((Definition) tokenList.get(0)).isEndState();
    }

    private boolean isMatchingRule(ArrayList<Token> subset, Rule rule)
    {
        boolean result = true;

        if(rule.length() == subset.size())
        {
            for(int i = 0; i < subset.size(); i ++)
            {
                if(subset.get(i) == null || !subset.get(i)._classification.equals(rule.get(i)))
                {
                    result = false;
                    break;
                }
            }
        }
        else
        {
            result = false;
        }

        return result;
    }

    public ArrayList<MeaningfulWord> wordsToTokens(String[] words)
    {
        MeaningfulWord[] tokens = new MeaningfulWord[words.length];

        for(int length = words.length; length > 0; length --) {
            WORDFINDER:
            for (int offset = 0; offset <= words.length - length; offset++) {
                String currentWord = "";

                for(int i = 0; i < length; i ++)
                {
                    if(words[offset + i] != null)
                    {
                        currentWord += words[offset + i];

                        if(i != length - 1)
                        {
                            currentWord += " ";
                        }
                    }
                    else
                    {
                        continue WORDFINDER; //just skip this word, there's a conflicting thing in the way
                    }
                }

                for(int i = 0; i < _words.size(); i ++)
                {
                    if(_words.get(i)._word.equals(currentWord) && !_words.get(i)._classification.equals("UKN")) //found 1, boss!
                    {
                        tokens[offset] = (MeaningfulWord) _words.get(i).copy();

                        for(int clearingIndex = 0; clearingIndex < length; clearingIndex ++)
                        {
                            words[clearingIndex + offset] = null;
                        }

                        break; //we're done with this word
                    }
                }
            }
        }

        ArrayList<MeaningfulWord> result = new ArrayList<MeaningfulWord>();

        for(int i = 0; i < tokens.length; i ++)
        {
            if(tokens[i] != null)
            {
                result.add(tokens[i]);
            }
        }

        return result;
    }

    public String printPayload(Token payload, int nest)
    {
        String message = "";

        for(int i = 0; i < nest; i ++)
        {
            message += "\t";
        }

        message += payload._classification;

        if(payload instanceof DefinitionPayload)
        {
            message += "\r\n";;

            DefinitionPayload dpd = (DefinitionPayload)payload;

            for(Token tok : dpd.payload)
            {
                message += printPayload(tok, nest + 1);
            }
        }
        else if(payload instanceof MeaningfulWord)
        {
            message += " - " + payload._word  + "(" + ((MeaningfulWord) payload).getPositiveness() + ")\r\n";
        }

        return message;
    }
}