let user = window.App.user;

module.exports = {
    /*updateReply(reply) {
        return reply.user_id === user.id;
    },
    makeAsBest(thread) {
        return thread.user_id === user.id
    },*/

    owns(model, prop = 'user_id') {
        return model['user_id'] === user.id;
    },

    isAdmin() {
        return ['Automaton', 'angle'].includes(user.name);
    }

};