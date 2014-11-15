/**
 * Created by pc on 11/14/2014.
 */
public class Token
{
    protected String _classification;
    protected String _word;

    public Token(String word, String classification)
    {
        this._classification = classification;
        this._word = word;
    }

    public Token copy()
    {
        Token token = new Token(_word, _classification);

        return token;
    }
}
