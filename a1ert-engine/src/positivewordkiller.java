import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.Scanner;

/**
 * Created by pc on 11/15/2014.
 */
public class positivewordkiller
{
    public static void main(String[] args) throws FileNotFoundException, UnsupportedEncodingException
    {
        Scanner input = new Scanner(new File("pwords.dat"));
        String line = input.nextLine();

        String[] wordsArr = line.split("( *, *| +(-|–) +)");

        PrintWriter output = new PrintWriter("pwordsformatted.dat", "UTF-8");

        for(String word : wordsArr)
        {
            output.println(word.toLowerCase());
        }

        output.close();
    }
}
