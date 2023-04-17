FROM httpd:2.4.53
COPY httpd.conf /usr/local/apache2/conf/httpd.conf
COPY extra/httpd-vhosts.conf /usr/local/apache2/conf/extra/httpd-vhosts.conf
CMD ["mkdir", "-p", "/usr/local/apache2/currency-api"]
