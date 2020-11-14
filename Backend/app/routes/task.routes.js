module.exports = app => {
    const tasks = require("../controllers/task.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Task
    router.post("/task", adminMiddleware.isAuthenticated ,tasks.createCustom);
  
    // // Retrieve all Task
    // router.get("/",tasks.findAll);

    // Retrieve all Task
    router.get("/task",adminMiddleware.isAuthenticated,tasks.findAllforMerchant);

  
    // // Retrieve a single Task with id
    // router.get("/", tasks.findOne);
  
    // Update a Task with id
    router.put("/task", adminMiddleware.isAuthenticated, tasks.chainedUpdate);
  
    // Delete a Task with id
    //router.delete("/", tasks.delete);
  
    // // Create a new Task
    // router.delete("/", tasks.deleteAll);
  
    app.use('/merchant/', router);
  };