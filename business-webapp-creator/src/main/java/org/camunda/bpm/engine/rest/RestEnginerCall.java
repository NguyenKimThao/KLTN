package org.camunda.bpm.engine.rest;

import java.io.ByteArrayInputStream;
import java.lang.management.ManagementFactory;
import java.net.InetAddress;
import java.util.Set;

import javax.management.MBeanServer;
import javax.management.MalformedObjectNameException;
import javax.management.ObjectName;
import javax.management.Query;
import javax.ws.rs.core.MediaType;
import org.apache.commons.fileupload.MultipartStream;

import org.apache.http.*;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.entity.mime.FormBodyPart;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicHttpRequest;
import org.apache.http.protocol.HTTP;
import org.camunda.bpm.engine.rest.RestEnginerCurrentService.AuthUser;
import org.springframework.web.multipart.MultipartHttpServletRequest;

public class RestEnginerCall {

    public String host = "localhost";
    public int port = 8088;
    public String protocol = "http";
    public HttpHost httpHost;
    public AuthUser user;

    public RestEnginerCall() {
        MBeanServer beanServer = ManagementFactory.getPlatformMBeanServer();
        Set<ObjectName> objectNames;
        try {
            objectNames = beanServer.queryNames(new ObjectName("*:type=Connector,*"), Query.match(Query.attr("protocol"), Query.value("HTTP/1.1")));
            host = InetAddress.getLoopbackAddress().getHostAddress();
            port = Integer.parseInt(objectNames.iterator().next().getKeyProperty("port"));
            protocol = objectNames.iterator().next().getKeyProperty("protocol");
            httpHost = new HttpHost(host, port, protocol);
        } catch (MalformedObjectNameException e) {
            e.printStackTrace();
        }
    }

    public RestEnginerCall(AuthUser user) {
        this.user = user;
        MBeanServer beanServer = ManagementFactory.getPlatformMBeanServer();
        Set<ObjectName> objectNames;
        try {
            objectNames = beanServer.queryNames(new ObjectName("*:type=Connector,*"), Query.match(Query.attr("protocol"), Query.value("HTTP/1.1")));
            host = InetAddress.getLoopbackAddress().getHostAddress();
            port = Integer.parseInt(objectNames.iterator().next().getKeyProperty("port"));
            protocol = objectNames.iterator().next().getKeyProperty("protocol");
            httpHost = new HttpHost(host, port, protocol);
        } catch (MalformedObjectNameException e) {
            e.printStackTrace();
        }
    }

    public String execute(String url) {
        DefaultHttpClient httpclient = new DefaultHttpClient();
        try {
            // specify the get request
            HttpGet getRequest = new HttpGet(url);
            getRequest.setHeader("Authorization", user.auth);
            HttpResponse httpResponse = httpclient.execute(httpHost, getRequest);
            if (httpResponse.getStatusLine().getStatusCode() != 200) {
                return null;
            }
            ResponseHandler<String> handler = new BasicResponseHandler();
            String body = handler.handleResponse(httpResponse);
            return body;
        } catch (Exception e) {
            return null;
        } finally {
            httpclient.getConnectionManager().shutdown();
        }
    }

    public String execute(String url, String method) {
        DefaultHttpClient httpclient = new DefaultHttpClient();
        try {
            BasicHttpRequest res = new BasicHttpRequest(method, url);;
            // specify the get request
            res.setHeader("Authorization", user.auth);
            HttpResponse httpResponse = httpclient.execute(httpHost, res);
            if (httpResponse.getStatusLine().getStatusCode() != 200) {
                return null;
            }
            ResponseHandler<String> handler = new BasicResponseHandler();
            String body = handler.handleResponse(httpResponse);
            return body;
        } catch (Exception e) {
            return null;
        } finally {
            httpclient.getConnectionManager().shutdown();
        }
    }

    public String executeWithPostData(String url, String type, String data) {
        DefaultHttpClient httpclient = new DefaultHttpClient();
        try {
//     
            HttpPost httppost = new HttpPost(url);
            httppost.setHeader("Authorization", user.auth);
            StringEntity params = new StringEntity(data);
            params.setContentType(type);
            httppost.setEntity(params);
            httppost.setHeader(HTTP.CONTENT_TYPE, type);
            HttpResponse httpResponse = httpclient.execute(httpHost, httppost);
            ResponseHandler<String> handler = new BasicResponseHandler();
            String body = handler.handleResponse(httpResponse);
            System.out.println(httpResponse.getStatusLine().getStatusCode());
            System.out.println(body);
            if (httpResponse.getStatusLine().getStatusCode() != 200) {
                return null;
            }
            return body;
        } catch (Exception e) {
            return null;
        } finally {
            httpclient.getConnectionManager().shutdown();
        }
    }

}
