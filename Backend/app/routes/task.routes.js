module.exports = app => {
    const tasks = require("../controllers/task.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Task Action
    router.post("/task", adminMiddleware.isAuthenticated ,tasks.createCustom);
  
    // // Retrieve all Task Actions
    // router.get("/",tasks.findAll);

    // Retrieve all Task Actions
    router.get("/task",adminMiddleware.isAuthenticated,tasks.findAllforMerchant);

  
    // // Retrieve a single Task Action with id
    // router.get("/", tasks.findOne);
  
    // Update a Task Action with id
    router.put("/task", adminMiddleware.isAuthenticated, tasks.update);
  
    // Delete a Task Action with id
    //router.delete("/", tasks.delete);
  
    // // Create a new Task Action
    // router.delete("/", tasks.deleteAll);
  
    app.use('/merchant/', router);
  };