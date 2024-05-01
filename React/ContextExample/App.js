import React, { Component } from 'react';
import { UserProvider } from './CustomProvider';
import UserInfo from './UserInfo';
class App extends Component {


    render(){
        return (
            <UserProvider>
               <div>
                    <h1>Context API Example</h1>
                    <UserInfo/>
               </div>
            </UserProvider>
        )
    }
}

// Default export
export default App;