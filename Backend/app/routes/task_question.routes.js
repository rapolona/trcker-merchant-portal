module.exports = app => {
    const task_questions = require("../controllers/task_question.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Campaign Task Action
    router.post("/campaign/task", adminMiddleware.isAuthenticated, task_questions.create);
  
    // // Retrieve all Campaign Task Actions. 
    // router.get("/", task_questions.findAll);
  
    // // Retrieve a single Campaign Task Action with id
    // router.get("/:id", task_questions.findOne);
  
    // Update a Campaign Task Action with id
    router.put("/campaign/task",adminMiddleware.isAuthenticated, task_questions.update);
  
    // Delete a Campaign Task Action with id
    router.delete("/campaign/task",adminMiddleware.isAuthenticated ,task_questions.delete);
  
    // // Create a new Campaign Task Action
    // router.delete("/", task_questions.deleteAll);
  
    app.use('/merchant/', router);
  };