module.exports = app => {
    const task_tickets = require("../controllers/task_ticket.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js");
    var router = require("express").Router();

    router.put("/approve", adminMiddleware.isAuthenticated, task_tickets.approve);
    router.put("/reject", adminMiddleware.isAuthenticated, task_tickets.reject);
  
    app.use('/merchant', router);
  };