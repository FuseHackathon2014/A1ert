import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import java.io.*;
import java.util.ArrayList;
import java.util.Scanner;

/**
 * Created by pc on 11/14/2014.
 */
public class Main
{
    private static ArrayList<MeaningfulWord> wordbank = new ArrayList<MeaningfulWord>();
    static ArrayList<String> nwords = new ArrayList<String>();
    static ArrayList<String> pwords = new ArrayList<String>();
    static ArrayList<MeaningfulWord> mwords = new ArrayList<MeaningfulWord>();
    private static final String ISADJ = "aj.";
    private static final String ISADV = "av.";
    private static final String ISART = "at.";
    private static final String ISCONJ = "cj.";
    private static final String ISDET = "d..";
    private static final String ISNEG = "xx0";
    private static final String ISNOUN = "(n..|pn.)";
    private static final String ISPROPER = "np0";
    private static final String ISPREP = "pr.";
    private static final String ISTO = "to0";
    private static final String ISVERB = "v..";

    public static void main(String[] args) throws FileNotFoundException, UnsupportedEncodingException
    {
        ArrayList<Definition> definitions = new ArrayList<Definition>();
        ArrayList<Token> targetWords = new ArrayList<Token>();
        String sentence = "";
        ParseEngine engine = new ParseEngine(wordbank, definitions);

        parseRules("rules.xml", definitions);
        populateDictionary();

        if(args.length > 1)
        {
            sentence = args[0];

            targetWords = (ArrayList<Token>)engine.wordsToTokens(args[1].split(", *")).clone();
        }

        parseRules("rules.xml", definitions);
        populateDictionary();

        ArrayList<Token> wordTokens = (ArrayList<Token>)engine.wordsToTokens(sentence.split(" ")).clone();
        engine.parseSentence(wordTokens, 0);

        boolean containsTargetWords = containsTargetWords(wordTokens, targetWords);

        System.out.println(engine.getConnotation(wordTokens) + "," + containsTargetWords);
    }

    /**
     * determins if at least 1 target word was used
     * @param tokens
     * @param targetWords
     * @return
     */
    private static boolean containsTargetWords(ArrayList<Token> tokens, ArrayList<Token> targetWords)
    {
        boolean contains = false;

        MAIN: for(Token token : tokens)
        {
            if(token instanceof DefinitionPayload)
            {
                DefinitionPayload payload = (DefinitionPayload) token;

                contains |= containsTargetWords(payload.payload, targetWords);

                if(contains) break;
            }
            if(token instanceof MeaningfulWord)
            {
                for(Token target : targetWords)
                {
                    if(token._word.equals(target._word))
                    {
                        contains = true;
                        break MAIN;
                    }
                }
            }
        }

        return contains;
    }

    private static boolean isPositive(String word)
    {
        boolean positive = false;

        for(String pword : pwords)
        {
            if(word.equals(pword))
            {
                positive = true;
                break;
            }
        }

        return positive;
    }

    private static boolean isNegative(String word)
    {
        boolean negative = false;

        for(String nword : nwords)
        {
            if(word.equals(nword))
            {
                negative = true;
                break;
            }
        }

        return negative;
    }

    private static void parseRules(String filename, ArrayList<Definition> defs)
    {
        DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
        Document dom = null;
        try {
            DocumentBuilder db = dbf.newDocumentBuilder();

            dom = db.parse(filename);

        }catch(ParserConfigurationException pce) {
            pce.printStackTrace();
        }catch(SAXException se) {
            se.printStackTrace();
        }catch(IOException ioe) {
            ioe.printStackTrace();
        }

        Element rootNode = dom.getDocumentElement();

        NodeList definitions = rootNode.getElementsByTagName("definition");

        for(int i = 0; i < definitions.getLength(); i ++)
        {
            Definition definition;
            Element def = (Element)definitions.item(i);

            String name = def.getAttribute("name");
            boolean endState = def.getAttribute("end").equals("true");

            definition = new Definition(name, endState);
            defs.add(definition);

            NodeList rules = def.getElementsByTagName("rule");

            for(int j = 0; j < rules.getLength(); j ++)
            {
                Rule rule = new Rule();
                definition.add(rule);

                Element rulElem = (Element)rules.item(j);

                NodeList tokens = rulElem.getElementsByTagName("token");

                for(int k = 0; k < tokens.getLength(); k ++)
                {
                    Element token = (Element)tokens.item(k);

                    String value = token.getFirstChild().getNodeValue();
                    rule.add(new Token("", value));
                }
            }
        }
    }

    private static void populateDictionary() throws FileNotFoundException
    {
        Scanner input = new Scanner(new File("newWordSet.dat"));

        while(input.hasNextLine())
        {
            String[] line = input.nextLine().split("\t");
            MeaningfulWord word = new MeaningfulWord(line[0], line[1], Integer.parseInt(line[2]));
            wordbank.add(word);
        }

        input.close();
    }


    //word parse
    /*
    sub '_' for ' '
    ADJECTIVE = aj*
    ARTICLE = AT*
    ADVERB = av* (,avp)
    PREPOSITION = (avp,) PR*
    CONJUNCATION = CJ*
    DET = DP*, DT*
    NOUN = N*
    PROPER NOUN = NP0 (need to auto capitalize)
    PRONOUN (noun) = PN*
    TO = TO(ZERO)
    VERB = V**
    NOT = XX0
    ALPHA = ZZ0 (ignore atm)
     */

    public static void parseWords() throws FileNotFoundException
    {
        Scanner input = new Scanner(new File("wordset.dat"));
        while(input.hasNext())
        {
            String lineIn = input.nextLine();
            System.out.println("LINE: " + lineIn);
            String[] line = lineIn.split(" ");

            String word = line[1];
            word = word.replaceAll("_", " "); //fix the lining problem

            String pos = line[2];

            if(pos.matches(ISADJ))
            {
                pos = "ADJ";
            }
            else if(pos.matches(ISADV))
            {
                pos = "ADV";
            }
            else if(pos.matches(ISART))
            {
                pos = "ART";
            }
            else if(pos.matches(ISCONJ))
            {
                pos = "CONJ";
            }
            else if(pos.matches(ISDET))
            {
                pos = "DET";
            }
            else if(pos.matches(ISNEG)) //NOT
            {
                pos = "NEG";
            }
            else if(pos.matches(ISNOUN))
            {
                if(pos.matches(ISPROPER))
                {
                    word = word.substring(0, 1).toUpperCase() + word.substring(1); //capitalize
                }

                pos = "N";
            }
            else if(pos.matches(ISPREP))
            {
                pos = "PREP";
            }
            else if(pos.matches(ISTO))
            {
                pos = "TO";
            }
            else if(pos.matches(ISVERB))
            {
                pos = "V";
            }
            else
            {
                pos = "UKN";
            }

            MeaningfulWord mword = new MeaningfulWord(word, pos, 0);
            wordbank.add(mword);
        }

        input.close();
    }

    public static void saveWords() throws FileNotFoundException, UnsupportedEncodingException
    {
        PrintWriter writer = new PrintWriter("newWordSet.dat", "UTF-8");

        for(MeaningfulWord word : wordbank)
        {
            writer.println(word._word + "\t" + word._classification + "\t" + word.getPositiveness()); //tab separated
        }

        writer.close();
    }

}
