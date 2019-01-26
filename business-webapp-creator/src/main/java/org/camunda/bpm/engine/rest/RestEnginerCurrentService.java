/* Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package org.camunda.bpm.engine.rest;

import java.util.HashMap;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContext;
import org.springframework.security.core.context.SecurityContextHolder;

/**
 * @author Daniel Meyer
 *
 */
public class RestEnginerCurrentService {

    public static class AuthUser {

        public String username;
        public String password;
        public String auth;
    }

    public static HashMap<String, RestEnginerCall> mapAuth = new HashMap<>();

    public static boolean setAuthorize(AuthUser username) {
        mapAuth.put(username.username, new RestEnginerCall(username));
        return true;
    }

    public static RestEnginerCall getRestEngineCurrent() {
        String name = SecurityContextHolder.getContext().getAuthentication().getName();
        return mapAuth.get(name);
    }
}
