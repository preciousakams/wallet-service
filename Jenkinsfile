def agentLabel= "devslave"
def branchName = BRANCH_NAME

if (branchName == "staging") {
    agentLabel = "staging"
} else if(branchName == "master") {
    agentLabel = "prod1"
} else {
    agentLabel = "devslave"
}

def committer = "";
pipeline {

    agent {label agentLabel}
    stages {
        stage ('Initialize') {
            steps {
                script {
                    sh 'git log --format="%ae" | head -1 > commit-author.txt'
                    committer = readFile('commit-author.txt').trim()
                    def msg = "STARTED: ${env.JOB_NAME} #${env.BUILD_NUMBER} by ${committer}: \n${env.BUILD_URL}"
                }
                sh 'rm -rf .env'
                sh 'rm -rf docker-compose.yml'
                sh 'cp /opt/.env_auth .env'
                sh 'cp /opt/docker-compose-wallet.yml docker-compose.yml'
                sh '''
                    echo "PATH = ${PATH}"
                '''
            }
        }
        stage('Deployment') {
            stages {
                   stage('Build & Push') {
                       steps{

                           sh "docker-compose build --no-cache"
                       }
                   }
                   stage('Start Service') {
                       environment {
                           TAG = "${env.BRANCH_NAME}"
                       }
                       steps{
                           sh "docker-compose up -d"
                           sh "docker-compose run wallet-service php artisan migrate --force --no-interaction"
                       }
                   }
            }
        }



    }

}
