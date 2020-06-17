package com.example.goraitest;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.google.android.material.floatingactionbutton.ExtendedFloatingActionButton;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ProfileActivity extends AppCompatActivity implements View.OnClickListener {

    private EditText firstName, lastName, inputPassword;
    private ExtendedFloatingActionButton btnUpdate, btnLogOut, btnDelete;
    private ProgressDialog progressDialog;
    private String userEmail;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);

        if (!SharedPrefManager.getInstance(this).isLoggedIn()) {
            finish();
            startActivity(new Intent(this, LoginActivity.class));
        }

        firstName = findViewById(R.id.update_first_name);
        lastName = findViewById(R.id.update_last_name);
        EditText inputEmail = findViewById(R.id.update_input_email);
        inputPassword = findViewById(R.id.update_input_password);
        btnUpdate = findViewById(R.id.btn_update);
        btnLogOut = findViewById(R.id.btn_logout);
        btnDelete = findViewById(R.id.btn_delete);
        TextView textViewUserName = findViewById(R.id.textViewUserName);
        progressDialog = new ProgressDialog(this);
        btnUpdate.setOnClickListener(this);
        btnLogOut.setOnClickListener(this);
        btnDelete.setOnClickListener(this);

        String name = "Welcome, " + SharedPrefManager.getInstance(this).getFName() + " " +
                SharedPrefManager.getInstance(this).getLName();
        textViewUserName.setText(name);
        userEmail = SharedPrefManager.getInstance(this).getEmail();
        firstName.setText(SharedPrefManager.getInstance(this).getFName());
        lastName.setText(SharedPrefManager.getInstance(this).getLName());
        inputEmail.setText(userEmail);
        inputPassword.setText(SharedPrefManager.getInstance(this).getPassword());
    }

    @Override
    public void onClick(View v) {
        if (v == btnUpdate)
            updateUser();
        else if (v == btnLogOut) {
            SharedPrefManager.getInstance(this).logout();
            finish();
            startActivity(new Intent(this, LoginActivity.class));
        } else if (v == btnDelete) {
            deleteUser();
        }
    }

    private void deleteUser() {
        final String email = userEmail;
        progressDialog.setMessage("Deleting User");
        progressDialog.show();
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                Constants.URL_DELETE,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        progressDialog.dismiss();

                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            if (!jsonObject.getBoolean("error")) {
                                Toast.makeText(
                                        getApplicationContext(),
                                        jsonObject.getString("message"),
                                        Toast.LENGTH_LONG).show();
                                SharedPrefManager.getInstance(getApplicationContext()).logout();
                                finish();
                                startActivity(new Intent(getApplicationContext(), LoginActivity.class));
                            } else {
                                Toast.makeText(
                                        getApplicationContext(),
                                        jsonObject.getString("message"),
                                        Toast.LENGTH_LONG).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.hide();
                        Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("email", email);
                return params;
            }
        };
        RequestHandler.getInstance(this).addToRequestQueue(stringRequest);
    }

    private void updateUser() {
        final String F_name = firstName.getText().toString().trim();
        final String L_name = lastName.getText().toString().trim();
        final String email = userEmail;
        final String password = inputPassword.getText().toString().trim();

        progressDialog.setMessage("Updating User Details");
        progressDialog.show();
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                Constants.URL_UPDATE,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        progressDialog.dismiss();
                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            Toast.makeText(getApplicationContext(), jsonObject.getString("message"), Toast.LENGTH_LONG).show();
                            SharedPrefManager.getInstance(getApplicationContext()).logout();
                            finish();
                            startActivity(new Intent(getApplicationContext(), LoginActivity.class));
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressDialog.hide();
                        Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                params.put("F_name", F_name);
                params.put("L_name", L_name);
                params.put("email", email);
                params.put("password", password);
                return params;
            }
        };
        RequestHandler.getInstance(this).addToRequestQueue(stringRequest);
    }
}