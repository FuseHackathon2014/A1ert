/**
 * Created by pc on 11/15/2014.
 */
public class MeaningfulWord extends Word
{
    int _positiveness;

    public MeaningfulWord(String name, String classification, int positive)
    {
        super(name, classification);
        this._positiveness = positive;
    }

    public int getPositiveness()
    {
        return _positiveness;
    }

    public void setPositiveness(int positiveness)
    {
        this._positiveness = positiveness;
    }

    @Override
    public MeaningfulWord copy()
    {
        MeaningfulWord word = new MeaningfulWord(_word, _classification, getPositiveness());
        return word;
    }

    @Override
    public String toString()
    {
        return _word + "\t" + _classification + "\t" + _positiveness;
    }
}
