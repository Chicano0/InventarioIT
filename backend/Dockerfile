FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    unixodbc \
    unixodbc-dev \
    libpq-dev \
    gnupg2 \
    curl \
    apt-transport-https \
    && rm -rf /var/lib/apt/lists/*

# Agregar repositorio de Microsoft
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update

# Instalar Microsoft ODBC Driver 18 for SQL Server
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18

# Instalar herramientas de SQL Server
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools18
RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc

# Instalar extensión PDO_ODBC para PHP
RUN docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr \
    && docker-php-ext-install pdo_odbc

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html

# Dar permisos
RUN chmod -R 755 /var/www/html

# Exponer puerto
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]