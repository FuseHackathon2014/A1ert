package com.iismathwizard.A1ert;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.speech.RecognizerIntent;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.Locale;

public class MainActivity extends Activity
{
    private final int REQUEST_VOICE = 1337;
    /**
     * Called when the activity is first created.
     */
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);

        //standard procedure to get voice input
        Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        intent.putExtra(RecognizerIntent.EXTRA_PROMPT, getResources().getString(R.string.record_message));
        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE, Locale.getDefault());

        try
        {
            startActivityForResult(intent, REQUEST_VOICE);
        }
        catch(Exception e)
        {
            //error out and inform the user something went wrong
        }

    }

    protected void onActivityResult(int requestCode, int resultCode, Intent data)
    {
        super.onActivityResult(requestCode, resultCode, data);

        if(resultCode == RESULT_OK && data != null)
        {
            ArrayList<String> result = data
                    .getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
            String resultText = result.get(0);
            TextView txtView = (TextView)findViewById(R.id.message);
            txtView.setText(resultText);

            //perform tap action then use the intent

            Intent intent = new Intent(this, SendingActivity.class);
            intent.putExtra(SendingActivity.NOTE, resultText);

            startActivity(intent);
            finish();
        }
    }
}
