import java.util.ArrayList;

/**
 * Created by pc on 11/14/2014.
 */
public class Rule
{
    private ArrayList<Token> _definition = new ArrayList<Token>();

    /**
     * can string the add commands together to define a rule
     * @param token
     * @return
     */
    public Rule add(Token token)
    {
        _definition.add(token);
        return this;
    }

    public int length()
    {
        return _definition.size();
    }

    /**
     * returns a classification at that spot in the rule
     * @param index
     * @return
     */
    public String get(int index)
    {
        return _definition.get(index)._classification;
    }
}