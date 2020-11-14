module.exports = app => {
    const admins = require("../controllers/admin.controller.js");
    const admindetails = require("../controllers/admindetail.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js");
    const adminsessions = require("../controllers/adminsession.controller");

    var router = require("express").Router();

    router.post("/admin/create", admins.create);

    router.post("/auth", admins.findAdminByCredential);

    router.get("/admin/find", adminMiddleware.isAuthenticated,admindetails.findOne);

    router.post("/logout", adminMiddleware.isAuthenticated, adminsessions.destroySession);

    router.post("/refresh", adminMiddleware.isAuthenticated, admins.refreshToken);

    app.use('/merchant/', router);
}