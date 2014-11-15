import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Scanner;

/**
 * Created by pc on 11/15/2014.
 */
public class positivityUpdator
{
    static ArrayList<String> nwords = new ArrayList<String>();
    static ArrayList<String> pwords = new ArrayList<String>();
    static ArrayList<MeaningfulWord> mwords = new ArrayList<MeaningfulWord>();

    public static void main(String[] args) throws FileNotFoundException, UnsupportedEncodingException
    {
        Scanner input = new Scanner(new File("nwordsformatted.dat"));

        while(input.hasNextLine())
        {
            nwords.add(input.nextLine());
        }

        input.close();
        input = new Scanner(new File("pwordsformatted.dat"));

        while(input.hasNextLine())
        {
            pwords.add(input.nextLine());
        }

        input.close();
        input = new Scanner(new File("newWordSet.dat"));

        while(input.hasNextLine())
        {
            String[] line = input.nextLine().split("\t");
            MeaningfulWord word = new MeaningfulWord(line[0], line[1], Integer.parseInt(line[2]));
            mwords.add(word);
        }

        input.close();

        (new File("newWordSet.dat")).delete();

        PrintWriter output = new PrintWriter("newWordSet.dat", "UTF-8");

        for(MeaningfulWord mword : mwords)
        {
            if(isPositive(mword._word))
            {
                mword.setPositiveness(1);
            }
            else if(isNegative(mword._word))
            {
                mword.setPositiveness(-1);
            }
            else
            {
                mword.setPositiveness(0);
            }

            output.println(mword.toString());
        }

        output.close();
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
}
