import java.util.ArrayList;

/**
 * Created by pc on 11/14/2014.
 */
public class Definition extends Token
{
    private ArrayList<Rule> _daRules = new ArrayList<Rule>();
    private boolean _endState;

    public Definition(String name, boolean endState)
    {
        super(null, name);
        this._endState = endState;
    }

    public void add(Rule rule)
    {
        _daRules.add(rule);
    }

    public Rule getRule(int index)
    {
        return _daRules.get(index);
    }

    public int length()
    {
        return _daRules.size();
    }

    public void remove(Rule rule)
    {
        _daRules.remove(rule);
    }

    public boolean isEndState()
    {
        return _endState;
    }
}
