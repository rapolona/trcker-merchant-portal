module.exports = app => {
    const campaign_task_actions = require("../controllers/campaign_task_action.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // // Create a new Campaign Task Action
    // router.post("/", adminMiddleware.isAuthenticated, campaign_task_actions.create);
  
    // // Retrieve all Campaign Task Actions. 
    // router.get("/", campaign_task_actions.findAll);
  
    // // Retrieve a single Campaign Task Action with id
    // router.get("/:id", campaign_task_actions.findOne);
  
    // Update a Campaign Task Action with id
    router.put("/campaign/task",adminMiddleware.isAuthenticated, campaign_task_actions.update);
  
    // Delete a Campaign Task Action with id
    router.delete("/campaign/task",adminMiddleware.isAuthenticated ,campaign_task_actions.delete);
  
    // // Create a new Campaign Task Action
    // router.delete("/", campaign_task_actions.deleteAll);
  
    app.use('/merchant/', router);
  };