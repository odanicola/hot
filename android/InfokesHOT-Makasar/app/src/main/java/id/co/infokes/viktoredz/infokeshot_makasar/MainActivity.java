package id.co.infokes.viktoredz.infokeshot_makasar;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import android.support.design.widget.NavigationView;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

@SuppressLint("SetJavaScriptEnabled")
public class MainActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener {

    WebView webView = null;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);

        LayoutInflater inflater = getLayoutInflater();
        View layout = inflater.inflate(R.layout.activity_info,
                (ViewGroup) findViewById(R.id.custom_toast_container));

        Toast toast = new Toast(getApplicationContext());
        toast.setGravity(Gravity.CENTER_VERTICAL, 0, 0);
        toast.setDuration(Toast.LENGTH_LONG);
        toast.setView(layout);
        toast.show();

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.setDrawerListener(toggle);
        toggle.syncState();

        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);

        webView = (WebView) findViewById(R.id.webView);
        webView.loadUrl("http://36.66.87.78/hot/");
        webView.setWebViewClient(new MainWebViewClient(){
            public boolean shouldOverrideUrlLoading(WebView view, String url){
                webView.loadUrl(url);

                findViewById(R.id.imageLoading1).setVisibility(View.VISIBLE);
                findViewById(R.id.webView).setVisibility(View.GONE);

                return true;
            }
            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);

                findViewById(R.id.imageLoading1).setVisibility(View.GONE);
                findViewById(R.id.webView).setVisibility(View.VISIBLE);
            }
        });
        webView.getSettings().setJavaScriptEnabled(true);
    }

    private class MainWebViewClient extends WebViewClient {
        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            Log.i("Log", "loading: " + url);

            view.loadUrl(url);
            return true;
        }
    }

    @Override
    public void onBackPressed() {

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            webView = (WebView) findViewById(R.id.webView);
            if (webView.canGoBack()) {
                webView.goBack();
            }
            else
            {
                AlertDialog.Builder builder = new AlertDialog.Builder(this);
                builder.setMessage("Are you sure you want to exit?")
                        .setCancelable(false)
                        .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                finish();
                            }
                        })
                        .setNegativeButton("No", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {
                                dialog.cancel();
                            }
                        });
                AlertDialog alert = builder.create();
                alert.show();
            }


        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();

        if (id == R.id.action_reload) {
            webView = (WebView) findViewById(R.id.webView);
            webView.reload();
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        if (id == R.id.nav_home) {
            setContentView(R.layout.activity_main);
            Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
            setSupportActionBar(toolbar);

            DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
            ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                    this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
            drawer.setDrawerListener(toggle);
            toggle.syncState();

            NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
            navigationView.setNavigationItemSelectedListener(this);

            webView = (WebView) findViewById(R.id.webView);
            webView.loadUrl("http://36.66.87.78/hot/");
            webView.setWebViewClient(new MainWebViewClient(){
                public boolean shouldOverrideUrlLoading(WebView view, String url){
                    webView.loadUrl(url);

                    findViewById(R.id.imageLoading1).setVisibility(View.VISIBLE);
                    findViewById(R.id.webView).setVisibility(View.GONE);

                    return true;
                }

                @Override
                public void onPageFinished(WebView view, String url) {
                    super.onPageFinished(view, url);

                    findViewById(R.id.imageLoading1).setVisibility(View.GONE);
                    findViewById(R.id.webView).setVisibility(View.VISIBLE);
                }

            });
            webView.getSettings().setJavaScriptEnabled(true);

        } else if (id == R.id.nav_info) {
            LayoutInflater inflater = getLayoutInflater();
            View layout = inflater.inflate(R.layout.activity_info,
                    (ViewGroup) findViewById(R.id.custom_toast_container));

            Toast toast = new Toast(getApplicationContext());
            toast.setGravity(Gravity.CENTER_VERTICAL, 0, 0);
            toast.setDuration(Toast.LENGTH_LONG);
            toast.setView(layout);
            toast.show();

        } else if (id == R.id.nav_exit) {
            finish();
            System.exit(0);
        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }
}
