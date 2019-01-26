/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.rest;

import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

/**
 *
 * @author TramSunny
 */
public class RestEnginerCallDefault extends RestEnginerCall {

    public static RestEnginerCallDefault INSTANCE = new RestEnginerCallDefault();

    public HttpResponse executeDefault(String auth, String url) {
        DefaultHttpClient httpclient = new DefaultHttpClient();
        try {
            // specify the get request
            HttpGet getRequest = new HttpGet(url);
            getRequest.setHeader("Authorization", auth);
            HttpResponse httpResponse = httpclient.execute(httpHost, getRequest);
            return httpResponse;
        } catch (Exception e) {
            return null;
        } finally {
            httpclient.getConnectionManager().shutdown();
        }
    }
}
