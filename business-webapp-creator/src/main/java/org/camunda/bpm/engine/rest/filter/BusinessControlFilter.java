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
package org.camunda.bpm.engine.rest.filter;

import javax.servlet.*;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.web.context.request.RequestAttributes;
import org.springframework.web.context.request.RequestContextHolder;
import org.springframework.web.context.request.ServletRequestAttributes;

/**
 * <p>
 * Cache control filter setting "Cache-Control: no-cache" on all GET requests.
 *
 * @author Daniel Meyer
 *
 */
public class BusinessControlFilter implements Filter {

    public void init(FilterConfig filterConfig) throws ServletException {

    }

    public void doFilter(ServletRequest req, ServletResponse resp, FilterChain chain) throws IOException, ServletException {

        final HttpServletRequest request = (HttpServletRequest) req;
        final HttpServletResponse response = (HttpServletResponse) resp;
        if (request.getRequestURI().contains("/engine/default/")) {
            System.out.println(request.getRequestURI());
            request.getRequestedSessionId();
            System.out.println(request.getRequestedSessionId()) ;

            UsernamePasswordAuthenticationToken token = new UsernamePasswordAuthenticationToken("demo", "demo");
            SecurityContextHolder.getContext().setAuthentication(token);

            Authentication auth = SecurityContextHolder.getContext().getAuthentication();

            if (auth == null || auth.getName().equals("")) {
                System.out.println("Khong vao duoc");
                throw new ServletException("error no login");
            }
        }
        if ("GET".equals(request.getMethod()) && !request.getRequestURI().endsWith("xml")) {
            response.setHeader("Cache-Control", "no-cache");
        }

        chain.doFilter(req, resp);
    }

    public void destroy() {

    }

}
