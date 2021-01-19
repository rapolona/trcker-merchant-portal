const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Ticket_Audit = sequelize.define("task_ticket_audit", {
      task_ticket_audit_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      status_changed_to: {
        type: Sequelize.STRING(255),
        allowNull: false    
      },
      last_updated_by: {
        type: DataTypes.UUID,
      },
      last_updated_by_type: {
        type: Sequelize.STRING(64)
      },
      task_ticket_id:{
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'task_tickets',
          key: 'task_ticket_id'
        }  
      }
    });
    return Task_Ticket_Audit;
  };