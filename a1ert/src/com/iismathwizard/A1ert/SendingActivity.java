package com.iismathwizard.A1ert;

import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import org.apache.http.Header;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Timer;
import java.util.TimerTask;

/**
 * Created by pc on 11/14/2014.
 */
public class SendingActivity extends Activity
{
    public static final String NOTE = SendingActivity.class + ".NOTE";
    private static final String API_URL = "https://www.iismathwizard.com/api/a1ert/note/send/";
    private static final String USER_ID = "0";
    private static final String RESPONSE_DOMAIN = "status";
    private static final String RESPONSE_SUCCESS = "success";

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.sending);
    }

    public void onStart()
    {
        super.onStart();

        if(getIntent().hasExtra(NOTE))
        {
            String message = getIntent().getStringExtra(NOTE);
            sendNote(message);
        }
    }

    private void sendNote(String message)
    {
        //implement user ID to distinguish
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        params.put("userid", USER_ID);
        params.put("message", message);

        client.setConnectTimeout(0);
        client.setResponseTimeout(0);
        client.setTimeout(0);

        client.get(API_URL, params, new JsonHttpResponseHandler()
        {
            @Override
            public void onSuccess(int statusCode, Header[] headers, JSONObject response)
            {
                try
                {
                    Log.d("response", response.toString());
                    TextView txt = (TextView) findViewById(R.id.successText);
                    findViewById(R.id.sendingScreen).setVisibility(View.GONE);
                    findViewById(R.id.successScreen).setVisibility(View.VISIBLE);

                    if (response.getString(RESPONSE_DOMAIN).equals(RESPONSE_SUCCESS))
                    {
                        //success case
                        txt.setText(R.string.sending_success);
                    }
                    else
                    {
                        //something failed
                        txt.setText(R.string.sending_failed);
                    }
                } catch (JSONException e)
                {
                    //welp this isn't pleasant
                    Log.e("JSON Exception on ASYNC client", e.getMessage());
                }

                Timer timer = new Timer(true);
                timer.schedule(new CloseTask(), 2000);
            }
        });
    }

    private class CloseTask extends TimerTask
    {
        @Override
        public void run()
        {
            finish();
        }
    }
}
