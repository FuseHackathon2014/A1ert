import java.util.ArrayList;

/**
 * Created by pc on 11/14/2014.
 */
public class DefinitionPayload extends Definition
{
    public ArrayList<Token> payload = new ArrayList<Token>();

    public DefinitionPayload(Definition def)
    {
        super(def._classification, def.isEndState());
    }

    public void addToken(Token token)
    {
        payload.add(token);
    }
}
