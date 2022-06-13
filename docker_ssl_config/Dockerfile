# Use the Nginx image
FROM wyveo/nginx-php-fpm:php80


# Remove the default nginx.conf
#RUN rm /etc/nginx/conf.d/default.conf

# Replace with our own nginx.conf
#COPY nginx.conf /etc/nginx/conf.d/

 

COPY certificate.crt /etc/nginx/certificate.crt
COPY private.key /etc/nginx/private.key
COPY ca_bundle.crt /etc/nginx/ca_bundle.crt
EXPOSE 443