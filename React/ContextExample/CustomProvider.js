import React, { Component, createContext } from 'react';

const Context = createContext();
const UserConsumer = Context.Consumer;

class UserProvider extends Component {
    state = {
        user: {
            name: 'Your name',
            email: 'youremail@gmail.com',
        },
        isLogedIn: true
    }

    loginHandle = () = {
        this.setState({ isLogedIn: true })
    }

    logoutHandle = () = {
        this.setState({ isLogedIn: true })
    }

    render(){
        return (
            <Context.Provider
                value={{
                    ...this.state,
                    loginHandle: this.loginHandle,
                    logoutHandle: this.logoutHandle
                }}
            >
                {this.props.children}
            </Context.Provider>
        )
    }
}

// Naming Export 
export { UserProvider, UserConsumer, Context as UserContext };