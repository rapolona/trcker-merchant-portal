module.exports = app => {
    const task_tickets = require("../controllers/task_ticket.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js");
    var router = require("express").Router();

    router.put("/approve", adminMiddleware.isAuthenticated, task_tickets.approve);
    router.put("/reject", adminMiddleware.isAuthenticated, task_tickets.reject);
    router.get("/tasktickets", adminMiddleware.isAuthenticated,task_tickets.findTicketsByUser);
    router.get("/usertickets", adminMiddleware.isAuthenticated,task_tickets.findTicketsOfUser);
    router.get("/alltickets", adminMiddleware.isAuthenticated, task_tickets.findAllTicketsWithDetails);
    router.get("/ticketreport", adminMiddleware.isAuthenticated, task_tickets.findAllTicketsForReport);
    router.get("/nextAndPrev", adminMiddleware.isAuthenticated, task_tickets.getNextAndPrev)
    app.use('/merchant', router);
  };