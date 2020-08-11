module.exports = app => {
    const task_action_classifications = require("../controllers/task_action_classification.controller.js");
  
    var router = require("express").Router();
  
    // Create a new Merchant
    router.post("/", task_action_classifications.create);
  
    // Retrieve all task_action_classifications
    router.get("/", task_action_classifications.findAll);
  
    // Retrieve a single Merchant with id
    router.get("/:id", task_action_classifications.findOne);
  
    // Update a Merchant with id
    router.put("/:id", task_action_classifications.update);
  
    // Delete a Merchant with id
    router.delete("/:id", task_action_classifications.delete);
  
    // Create a new Merchant
    router.delete("/", task_action_classifications.deleteAll);
  
    app.use('/api/task_action_classification', router);
  };