/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package org.camunda.bpm.engine.controller;

import com.google.gson.Gson;
import org.apache.http.HttpResponse;
import org.camunda.bpm.engine.rest.RestEnginerCurrentService;
import org.camunda.bpm.engine.rest.RestEnginerCurrentService.AuthUser;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;
import org.camunda.bpm.engine.rest.*;

/**
 *
 * @author TramSunny
 */
@Controller
@ResponseBody
public class LoginController {

    @RequestMapping(value = "/authorize", method = RequestMethod.POST)
    public String authorize(@RequestBody String json) {
        try {

            System.out.println(json);
            Gson gson = new Gson();
            RestEnginerCurrentService.AuthUser user = gson.fromJson(json, RestEnginerCurrentService.AuthUser.class);

            HttpResponse httpResponse = RestEnginerCallDefault.INSTANCE.executeDefault(user.auth, "/engine-rest/process-definition");
            if (httpResponse.getStatusLine().getStatusCode() == 200) {
                RestEnginerCurrentService.setAuthorize(user);
                UsernamePasswordAuthenticationToken token = new UsernamePasswordAuthenticationToken(user.username, user.password);
                SecurityContextHolder.getContext().setAuthentication(token);
                return "{\"success\":\"true\"}";
            }
            return "{\"success\":\"false\"}";
        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
        return "{\"success\":\"false\"}";
    }
}
