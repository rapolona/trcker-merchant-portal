module.exports = app => {
    const task_actions = require("../controllers/task_action.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Task Action
    router.post("/", adminMiddleware.isAuthenticated ,task_actions.createCustom);
  
    // Retrieve all Task Actions
    router.get("/",task_actions.findAll);

    // Retrieve all Task Actions
    router.get("/merchant",adminMiddleware.isAuthenticated,task_actions.findAllforMerchant);

  
    // // Retrieve a single Task Action with id
    // router.get("/", task_actions.findOne);
  
    // Update a Task Action with id
    router.put("/", adminMiddleware.isAuthenticated, task_actions.update);
  
    // Delete a Task Action with id
    router.delete("/", task_actions.delete);
  
    // // Create a new Task Action
    // router.delete("/", task_actions.deleteAll);
  
    app.use('/api/task_action', router);
  };