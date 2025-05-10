# Installation Guide

### Step 1: Build the Database Container and Install App Dependencies

Start by building the `builder` container, which will set up the database and install dependencies for the `app-client` project:

```bash
docker-compose up -d --build builder
```

Note: Wait for the builder container to finish its process. It will automatically shut down once it's done. After that, you can safely remove the builder container.

### Step 2: Start All Services

Once the builder container has completed its task and been removed, you can start the remaining services:

```bash
docker-compose up -d --build rabbitmq whatsapp-receiver whatsapp-sender app-client
```

Please wait until the `app-client` service finishes building. This may take a few minutes depending on your system.

Once the build is complete, the application will be available at:

[http://localhost:8000](http://localhost:8000)
