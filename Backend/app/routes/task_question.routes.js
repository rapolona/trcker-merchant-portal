module.exports = app => {
    const task_questions = require("../controllers/task_question.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Task Question
    router.post("/campaign/task", adminMiddleware.isAuthenticated, task_questions.create);
  
    // // Retrieve all Task Question. 
    // router.get("/", task_questions.findAll);
  
    // // Retrieve a single Task Question with id
    // router.get("/:id", task_questions.findOne);
  
    // Update a Task Question with id
    router.put("/campaign/task",adminMiddleware.isAuthenticated, task_questions.update);
  
    // Delete a Task Question with id
    router.delete("/campaign/task",adminMiddleware.isAuthenticated ,task_questions.delete);
  
    // // Create a new Task Question
    // router.delete("/", task_questions.deleteAll);
  
    app.use('/merchant/', router);
  };