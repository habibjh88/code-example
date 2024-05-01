import { UserConsumer } from "./CustomProvider"

export default function UserInfo(){
    return (
        <UserConsumer>
            {({user})=>{
                <div>
                    <p>{user.name}</p>
                    <p>{user.email}</p>
                </div>
            }}
        </UserConsumer>
    )
}