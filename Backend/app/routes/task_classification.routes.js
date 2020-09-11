module.exports = app => {
    const task_classifications = require("../controllers/task_classification.controller.js");
  
    var router = require("express").Router();
  
    // Create a new Task Classification
    router.post("/", task_classifications.create);
  
    // Retrieve all Task Classification
    router.get("/", task_classifications.findAll);
  
    // Retrieve a single Task Classification with id
    router.get("/:id", task_classifications.findOne);
  
    // Update a Task Classification with id
    router.put("/:id", task_classifications.update);
  
    // Delete a Task Classification with id
    router.delete("/:id", task_classifications.delete);
  
    // Create a new Task Classification
    router.delete("/", task_classifications.deleteAll);
  
    app.use('/api/task_action_classification', router);
  };