import sys
import threading
import datetime
import socket


from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
#import undetected_chromedriver.v2 as uc
import undetected_chromedriver as uc

import time
import os
from os import listdir
from bs4 import BeautifulSoup
import requests , json

from random import randrange
import pyautogui


import asyncio


import ftplib   #pip install pyftpdlib

## chrome 116
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service

import subprocess


#스폰서 필터 
def filter_sponsor(q,turl):
    target_url = ''   
    try:            
        print(turl)        
        if turl  == "":
            #print("통과")
            pass                             
        elif "https" in turl:
            #print("https가있다")
            if q in turl :
                #print("매치한다")
                return "exist"
            else:
                #print("매치하지않음")
                target_url = turl
                return target_url
        else:
            pass            
    except:
        print("filter_sponsor_error")
        pass                
    return ""


def abuseClick(com_name,usr):
    adclick = ''
    try:
        #print("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr))
        adclick = requests.get("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr)).json()
        #print(adclick)
        if adclick:#adclick['gglkeyword']:
                        
            keyword = adclick['gglkeyword']
            mysite = adclick['mysite']
            home()
            time.sleep(0.5)
            chrome()
            time.sleep(3) 
            chrome_url_text(keyword)
            time.sleep(2)
            
            #print(str(s_x) + " " +str(s_y))
            
            sponimage = os.getenv('USERPROFILE')+"/Desktop/chinaM/sponsor.png"
            
            #sponsers = pyautogui.locateOnScreen(sponimage,confidence=0.7)
            #print(sponsers)
              
            pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
     
            imageT = ImageGrab.grab().crop((s_x,s_y,s_x+400,s_y+75))#add this value 718/127
            #imageT.save('c:/Users/user/ss.png')            
            imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)
            #print(imageS)  
            for i in range(0,len(mysite)):
                filterSponsor = filter_sponsor(mysite[i],imageS)
                if filterSponsor == "exist":
                    print("필터로 통과")
                    pass
                elif filterSponsor :
                    #print("광고클릭한다")
                    pyautogui.moveTo(s_x,s_y,1)
                    pyautogui.click(s_x,s_y)
                    time.sleep(4)
                    dna_scrolldown()
                    time.sleep(6)
                    #print("https://www.pwa.pe.kr/adclick/?mode=log&gglkeyword="+keyword+"&turl="+filterSponsor+"&com="+com_name+"&usr="+str(usr))
                    adlog = requests.get("https://www.pwa.pe.kr/adclick/?mode=log&gglkeyword="+keyword+"&turl="+filterSponsor+"&com="+com_name+"&usr="+str(usr))
                    #print(adlog)
                    return "adclick"
    except Exception as e:
        print(e)
        print("error")
        return 


def job(startline,idsetting,ipsetting,ibsetting,weekpath):
    while True:
        try:
            job_each(startline,idsetting,ipsetting,ibsetting,weekpath)

        except Exception as e:
            # Handle the exception or perform any necessary cleanup
            print(f"An error occurred: {e}")
            # Restart the thread
            time.sleep(30)
            thread = threading.Thread(target=job,args=[startline,idsetting,ipsetting,ibsetting,weekpath])
            thread.start()
            #thread.join() ## not ????

        time.sleep(1800)


   
def job_each(startline,idsetting,ipsetting,ibsetting,weekpath):

    com_name = socket.gethostname()
    msg = ''
    
    strstart_t = str(startline)
    strstart = strstart_t.zfill(2)   
    
    print(strstart +"user",  end =' ' )
    print(datetime.datetime.now(), end = " ")

    if ipsetting == "on":#아이피작동 
        try:
            f = open('ip.txt')
            lines = f.readlines()    
            proxy = lines[int(startline)]
            print(proxy , end =' ')
        except:
            print("ip.txt 파일을 실행화일 폴더로 넣어주세요")
            pass
    else:
        proxy = ''
        

    
    ##
    #profile_path
    ##
    if str(weekpath) == "0":
        weekpath = ""
    userpath = "c:/proxy"+str(weekpath)+"/"+strstart+"user"
    print(strstart+" 유저 폴더 "+str(userpath))
    try:
        os.mkdir(userpath)
    except:
        print(strstart+" 유저 폴더 존재.", end = " " )
        pass
        
    preference_file = userpath+"/Default/Preferences"
    
    if os.path.isfile(preference_file):
        print(strstart+"폴더내 캐시파일 존재" , end = " ")
        try:
            os.remove(preference_file)
            print(strstart+"캐시파일 삭제 완료.")
            pass
        except:
            print(strstart+"캐시파일 삭제 에러")
            pass
    cache_data = userpath+"/Default/Cache/Cache_Data"
    
    if os.path.exists(cache_data):
        for file in os.scandir(cache_data):
            os.remove(file.path)
        print(strstart+"캐시폴더 삭제 하였습니다")
    else:
        print(strstart+"캐시폴더가 없습니다")
            
    
    #options = uc.ChromeOptions()

    subprocess.Popen(r'C:\Program Files\Google\Chrome\Application\chrome.exe --remote-debugging-port=92'+str(strstart)+' --user-data-dir="'+userpath)
    options = webdriver.ChromeOptions()
    # setting profile
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--proxy-server='+proxy)
    
    #options.add_argument(r'user-data-dir='+userpath)
    #options.add_argument('--remote-debugging-port=92'+str(strstart))
    options.add_experimental_option("debuggerAddress", '127.0.0.1:92'+str(strstart))
    service=Service()

    


    #chrome open
    while True:    
        try:
            #driver = uc.Chrome(user_data_dir=userpath,options=options,use_subprocess=True)

            #driver = uc.Chrome(user_data_dir=userpath,options=options,use_subprocess=True)
            driver = webdriver.Chrome(service=service,options=options)
            
            driver.set_window_size(1200,600)
            ##구글 열리고 난뒤 대기시간
            
            driver.get('https://daum.net')

            time.sleep(10)
            driver.implicitly_wait(3)
            time.sleep(7)
            break
        except Exception as e:
            
            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=neterror&com="+com_name+"&usr="+strstart)
            print(strstart+"인터넷 연결안됨.10분뒤 재시도..")
            print(e)           
        time.sleep(600)    


    wpositions = 0
    hpositions = 0    
    
    ##sound setting
    if ibsetting == "on":
        try:
            close_new_tab(driver)
            
            driver.set_window_position(0, 0)
            driver.set_window_size(1200,600)        
            driver.get("chrome://settings/content/siteDetails?site=https%3A%2F%2Fwww.youtube.com%2F")
            driver.implicitly_wait(5)
            pyautogui.moveTo(1100,400,3)
            time.sleep(1)
            pyautogui.click()
            time.sleep(1)
            pyautogui.scroll(-500)
            time.sleep(1.5)
            pyautogui.click(x=824, y=466)
            time.sleep(0.5)
            pyautogui.click(x=841, y=510)
            time.sleep(5)
        except:
            requests.get("http://61.109.34.245/proxyserver/log.php?type=&ip="+proxy+"&error=seterror&com="+com_name+"&usr="+strstart)
            pass
            
        time.sleep(5)

    if int(startline) < 10:
        wpositions = 100*int(startline)
        hpositions = 0                   
        pass
    elif int(startline) < 20 and int(startline) >= 10:
        wpositions = 100*(int(startline) - 10)
        hpositions = 300
        pass
    elif int(startline) >= 20:
        wpositions = 100 * (int(startline) - 20 )
        hpositions = 600
        pass
    else:
        pass
    driver.set_window_position(wpositions, hpositions)
    driver.set_window_size(550, 600)    




    #adclick

    try:
        #print("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr))
        #adclick = requests.get("https://www.pwa.pe.kr/adclick/?mode=getadclick&type=proxy&com="+com_name+"&usr="+str(startline)).json()
        #print(adclick)
        #adclick['gglkeyword'] = "바카라사이트"
        #keyword = adclick['gglkeyword']
        keyword =  "바카라사이트"
        keyword =  ""
        #mysite = adclick['mysite']
        
        if keyword:
                        
            
            driver.get('https://www.google.com/search?q=바카라사이트')
            driver.implicitly_wait(3)
#//*[@id="tadsb"]/div/div/div/div/div[1]/a

            try:
                adtabs = driver.find_elements(By.CSS_SELECTOR,'#tadsb > div > div > div > div > div > a')
                print(adtabs)
                
            except:
                print("adtabs error")
                time.sleep(1)
            time.sleep(100000)
            try:
                WebDriverWait(driver, 10,2).until(EC.element_to_be_clickable((By.XPATH, '//*[@id="tadsb"]/div/div/div/div/div[1]/a'))).click()
                print("EC error")
                time.sleep(2)

            except:
                pass                    
                   
    except Exception as e:
        print(e)
        print("error")
        

        
       
    #google id check
    while True:
        #break
        try:
            driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")
            time.sleep(5)
            googleid = requests.get("http://61.109.34.245/proxyserver/proxy.php?type=&set=json&ip="+proxy+"&com="+com_name+"&usr="+strstart).json()
            #print(googleid)
            time.sleep(2)            
            print(str(strstart)+"번 "+str(idsetting)+" id")
            print(str(strstart)+"번 "+str(ipsetting)+" ip")
            print(str(strstart)+"번 "+str(ibsetting)+" ib")
            
                  
            
            if googleid['gtime'] and googleid['gid'] and idsetting == "onxxxx":
                driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")
                time.sleep(int(googleid['gtime']))
                break
            elif googleid['status'] and ipsetting == "on":
                print(str(strstart)+"번 "+'office wifi로 접속되었습니다. 프록시를 확인하세요')                
                pass            
            elif idsetting=="on" and ipsetting == "on":
                break
                #print(strstart+'발급된 id가 없습니다 30분뒤 재시도')
                #pass
            elif idsetting =="off" and ipsetting =="off":
                print(str(strstart)+"번 "+' id ip 테스트통과 ')
                break
            elif ibsetting == "off":
                print(str(strstart)+"번 "+' 빠른통과')                
                break            
        except Exception as e:
            print(e)
            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=nogid&com="+com_name+"&usr="+strstart)
            print(str(strstart)+"번 서버에서 id 확인 실패 30분뒤 재시도")
            
        driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")    
        time.sleep(1800)    
        #http://61.109.34.245/proxyserver/proxy.php?type=ip=121.126.253.10:6081&comDESKTOP-KG02JEB&usr=01

    #print('###############')   

    
    while True:
        try:
            while True:
                try:
                    driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")
                    time.sleep(5)                    
                    server = requests.get("http://61.109.34.245/proxyserver?type=proxy&proxy="+proxy+"&com="+com_name+"&usr="+strstart).json()
                    print(server)
                    print(" ")
                    if int(startline) < int(server['linecnt1']):
                        wpositions = int(server['wposition'])*int(startline)
                        hpositions = 0                   
                        pass
                    elif int(startline) < int(server['linecnt2']) and int(startline) >= int(server['linecnt1']):
                        wpositions = int(server['wposition'])*(int(startline) - int(server['linecnt1']))
                        hpositions = int(server['hposition'])
                        pass
                    elif int(startline) >= int(int(server['linecnt2'])):
                        wpositions = int(server['wposition']) * (int(startline) - int(server['linecnt2']) )
                        hpositions = int(server['hposition']) * 2
                        pass
                    else:
                        pass
                   
                    driver.set_window_position(wpositions, hpositions)
                    driver.set_window_size(int(server['width']), int(server['height']))
                            
                    if server['status'] == "healthy":                    
                        break
                    elif server['status'] == 'parking':                   
                        parkingurl = server['parkingurl']
                        parkingtime = int(server['parkingtime'])   
                        try:
                            driver.get(parkingurl)
                            driver.implicitly_wait(5) 
                            print(str(strstart)+"번 "+"user ["+parkingurl+"] "+str(parkingtime)+" 초 동안 주차시작")
                            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=parking&com="+com_name+"&usr="+strstart)
                            time.sleep(int(server['parkingtime']))
                        except:
                            print(str(strstart)+"번 "+"주차실패")
                            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=etc&com="+com_name+"&usr="+strstart)
                        
                except Exception as e:
                    print(e)
                    print(str(strstart)+"번 "+'브라우저별 설정을 가져오지 못했습니다.30분뒤 재시도')
                    time.sleep(1800)

                time.sleep(120)
                             
    
            time.sleep(3)
            delaytime = int(server['delaytime'])
            status = server['status']
            fireout = server['fireout']
            keyword = server['keyword']
            target = server['target']
            percent = server['percent']
            fireout = server['fireout'] 
            mode = server['mode']
            q = server['q']
            width = int(server['width'])
            height = int(server['height'])
            wposition = int(server['wposition'])
            hposition = int(server['hposition'])

            linecnt1 = int(server['linecnt1'])
            linecnt2 = int(server['linecnt2'])            
            browserinterval = int(server['browserinterval'])
            action_nbm = randrange(100)            
        
            while True:
                try:
                    driver.get('https://www.youtube.com')    
                    ## 유뷰트 주소 이동한 뒤 대기시간 
                    driver.implicitly_wait(5)    
                    time.sleep(15)

                    #### 2023 08 07 
                    ## break
                    
                    #get_vals from server
                    if idsetting == "on":
                        avatar = driver.find_element(By.ID,'avatar-btn')
                        if avatar:
                            requests.get("http://61.109.34.245/proxyserver/log.php?type=&ip="+proxy+"&loginstatus=on&com="+com_name+"&usr="+strstart)
                            print(str(strstart)+"번 "+'로그인감지')
                            break
                        else:
                            requests.get("http://61.109.34.245/proxyserver/log.php?type=&ip="+proxy+"&loginstatus=off&com="+com_name+"&usr="+strstart)
                            print(str(strstart)+"번 "+'로그인안됨 1시간뒤 재시도 ')
                            time.sleep(15)
                            driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")
                        
                except:
                    if idsetting == "on":
                        requests.get("http://61.109.34.245/proxyserver/log.php?type=&ip="+proxy+"&loginstatus=off&com="+com_name+"&usr="+strstart)
                        print(str(strstart)+"번 "+'로그인안됨 1시간뒤 재시도 ')
                        time.sleep(15)
                        driver.get("http://61.109.34.245/proxyserver/proxy.php?type=&ip="+proxy+"&com="+com_name+"&usr="+strstart+"&check=on")

            
                close_new_tab(driver)  

                time.sleep(3600)     
                  
                   

            
            if action_nbm >= int(percent):
                try:
                    zoom = driver.find_elements(By.CSS_SELECTOR,'#search-button-narrow > button#button')                
                    zoom[0].click()                    
                except:
                    time.sleep(1)
                try:
                    WebDriverWait(driver, 10,2).until(EC.element_to_be_clickable((By.XPATH, "//input[@name='search_query']"))).send_keys(keyword)
                    time.sleep(2)
                    driver.find_element(By.XPATH,'//*[@id="search-icon-legacy"]').click()
                    print(str(strstart)+ str(keyword) + " 입력 ",end=" ")
                    
                except Exception as e:
                    print(e)
                    pass
        
                #검색어입력 대기시간
                time.sleep(2)

                searchq = "/watch?v="+q

                #################
                ###### 실시간 필터부분 
                try:
                    if ( mode == "realtime" or mode == "creat")  and searchq not in driver.page_source:
                        #필터클릭 
                        driver.find_element(By.CSS_SELECTOR,'ytd-button-renderer.style-scope.ytd-search-header-renderer').click()
                        #ytd-button-renderer.style-scope.ytd-search-header-renderer
                        #ytd-toggle-button-renderer.style-scope.ytd-search-sub-menu-renderer       //2023 08 12 기존 코드.(이날자 이후로 0xo으로 사이즈 없어짐)
                        #yt-spec-button-shape-next yt-spec-button-shape-next--text yt-spec-button-shape-next--mono yt-spec-button-shape-next--size-m yt-spec-button-shape-next--icon-trailing 
                        time.sleep(2)
                    
                        wait = WebDriverWait(driver, 20)
                        xelements = driver.find_elements(By.CSS_SELECTOR,'a.yt-simple-endpoint.style-scope.ytd-search-filter-renderer')
                        for xelement in xelements:                        
                            xele = xelement.get_attribute('href')
                            if "&sp=EgJAAQ%253D%253D" in xele:
                                print(str(strstart)+"번 "+"realtime filter")
                                xelement.click()
                                break                        
                            else:
                                pass
                        ##실시간필터 클릭후 대기시간
                        time.sleep(3)
                    else:
                        pass
                except Exception as e:
                    print(e)
                    
                try:
                    if ( mode == "realtime" or mode == "creat")  and searchq not in driver.page_source:
                        
                        driver.execute_script("window.scrollTo(3, 783)")#랜덤한 수치로  맨위로 올린다                    
                        #최초페이지 스크롤
                        print(str(strstart)+"번 scroll ++++++++")
                        time.sleep(1.5)
                           
                        # 페이지 끝까지 스크롤
                        last_height = driver.execute_script("return document.documentElement.scrollHeight")
                        treeHit = 0
                        while treeHit < 10:
                            treeHit = treeHit +1
                            driver.execute_script("window.scrollTo(0, document.documentElement.scrollHeight);")
                            time.sleep(1)
                            new_height = driver.execute_script("return document.documentElement.scrollHeight")
                            if new_height == last_height:
                                break
                            last_height = new_height
                            if treeHit == 4:
                                break
                         ## 스크롤끝난다음에 대기시간   
                        time.sleep(3)
                    else:
                        pass
                except Exception as e:
                    print(e)
                    

                driver.implicitly_wait(5)


               
                #################
                ###### 크리에이터  필터부분 
                
                if ( mode == "realtime" or mode == "creat")  and searchq not in driver.page_source:
                    driver.execute_script("window.scrollTo(60, 810)")
                    driver.find_element(By.CSS_SELECTOR,'ytd-toggle-button-renderer.style-scope.ytd-search-sub-menu-renderer').click()
                
                    ##필터버튼 삼선버튼 클릭대기
                    time.sleep(3)
                
                    wait = WebDriverWait(driver, 20)
                    xelements = driver.find_elements(By.CSS_SELECTOR,'a.yt-simple-endpoint.style-scope.ytd-search-filter-renderer')
                    for xelement in xelements:                        
                        xele = xelement.get_attribute('href')
                        if "sp=EgIwAQ%253D%253D" in xele:
                            print("sp=EgIwAQ%253D%253D")
                            xelement.click()                            
                            break
                        else:
                            pass
                        
                    time.sleep(3)
                else:
                    pass
                  
                
                if ( mode == "realtime" or mode == "creat")  and searchq not in driver.page_source:   
                    driver.execute_script("window.scrollTo(134, 751)")
                    #최초페이지 스크롤
                    time.sleep(1.5)
                    
                    # 페이지 끝까지 스크롤
                    last_height = driver.execute_script("return document.documentElement.scrollHeight")
                    treeHit = 0
                    while treeHit < 10:
                        treeHit = treeHit +1
                        driver.execute_script("window.scrollTo(0, document.documentElement.scrollHeight);")
                        time.sleep(1)
                        new_height = driver.execute_script("return document.documentElement.scrollHeight")
                        if new_height == last_height:
                            break
                        last_height = new_height
                        if treeHit == 4:
                            break
                     ## 스크롤끝난다음에 대기시간   
                    time.sleep(3)
                else:
                    pass

                driver.implicitly_wait(5)

                
               
                try:
                    
                    if searchq in driver.page_source:
                        
                        
                        yl = driver.find_elements(By.CSS_SELECTOR,'a.yt-simple-endpoint.style-scope.ytd-video-renderer')                
                        for ys in yl:
                            try:
                                if q in ys.get_attribute('href'):
                                    #ys.click()
                                    ys.send_keys(Keys.ENTER)
                                    print(strstart+"user ["+keyword+"]검색에서 영상 클릭성공 ")
                                    break
                                else:
                                    pass                                
                            except:                                
                                #print(strstart+"user ["+keyword+"]검색에서 영상 클릭실패")
                                pass
                    else:
                        print(strstart+"user 검색 노출 없음")
                        #requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=nofindtarget")
                        ##강제오류 처리 . 맨처음 서버 접속으로 돌아간다..
                        if msg:
                            pass
                        else:
                            msg = "nofindtarget"                            
                        pass                    
                except:
                    print(strstart+"user 영상검색 에러. 주소 이동.")
                    #requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=failfindkeyword")
                    ##강제오류 처리 . 맨처음 서버 접속으로 돌아간다..                                    
                    if msg:
                        pass
                    else:
                        msg = "failfindkeyword"                    
                    raise
                
            else:
                requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=notinteract&com="+com_name+"&usr="+strstart)        
                print(strstart+"user 검색없이 주소  이동.")
                driver.get(target)
                pass


            
            driver.implicitly_wait(5)
            time.sleep(5)

            cq = ''


            close_new_tab(driver)

            
            #현재주소창이 맞다면
            if driver.current_url== "https://www.youtube.com/watch?v="+q:
                print(strstart+"user 주소 일치.")
                cq =  driver.current_url
                pass           
            else:
                #requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=etc")                
                print(strstart+"user 주소불일치.")
                if msg:
                    pass
                else:
                    msg = "etc"   
                raise


                
            print(datetime.datetime.now(),end = " " )
            #http://61.109.34.245/proxyserver/log.php?ip=1.250.210.84:8899&error=viewstart&com=pc001&usr=00&cq=https://www.youtube.com/watch?v=oJP79rbKCGQ
            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=viewstart&com="+com_name+"&usr="+strstart+"&cq="+cq)  
            print(strstart+"user 영상시청 시작..")



            close_new_tab(driver)
            
            #시청시간
            #time.sleep(int(fireout))            
            slt = divmod(int(fireout),browserinterval)
            for slte in range(slt[0]):
                time.sleep(browserinterval)
                close_new_tab(driver)
                tmpserver = requests.get("http://61.109.34.245/proxyserver/?mode=view&proxy="+proxy+"&com="+com_name+"&usr="+strstart).json()
                
                if q != tmpserver['q'] and tmpserver['q']:
                    requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=break&com="+com_name+"&usr="+strstart)    
                    print(strstart+"user 영상중단",end=" ")
                    break

            print(strstart+"user 영상시청 끝..")        
            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error=endview&com="+com_name+"&usr="+strstart)           
            time.sleep(20)
        except:
            if msg:
                pass
            else:
                msg = "critical"
            requests.get("http://61.109.34.245/proxyserver/log.php?ip="+proxy+"&error="+msg+"&com="+com_name+"&usr="+strstart)
            time.sleep(20)

        time.sleep(120)              




def undetec_del():
    undetec_path = os.getenv('USERPROFILE')+'/AppData/Roaming/undetected_chromedriver'
    try:
        if os.path.exists(undetec_path):
            for file in os.scandir(undetec_path):
                try:
                    os.remove(file.path)
                    print("언덱 파일 삭제")
                except:
                    print("언덱 파일 건너띔")
                    pass
            print("언덱 파일 처리")
        else:
            print("언덱 파일 없음")
    except:
        print("언덱 데이타 삭제")

def temp_del():
    temp_path = os.getenv('USERPROFILE')+'/AppData/Local/Temp'
    try:
        if os.path.exists(temp_path):
            for file in os.scandir(temp_path):
                try:
                    os.remove(file.path)
                    print("임시 파일 삭제")
                except:
                    print("임시 파일 건너띔")
                    pass
            print("임시 파일 처리")
        else:
            print("임시 파일 없음")
    except:
        print("임시 데이타 삭제")
        

def chrome_ver_check():
    try:
        url = 'chrome://settings/help'
        #os.system("start /max chrome  Chrome://setting/")
        #subprocess.Popen('"C:/Program Files (x86)/Google/Chrome/Application/chrome.exe" --start-maximized')
        subprocess.Popen('"C:/Program Files/Google/Chrome/Application/chrome.exe" --start-maximized')
        time.sleep(5)
        pyautogui.moveTo(1253,50,2)
        time.sleep(1)
        pyautogui.click()
        time.sleep(1)
        pyautogui.moveTo(1090,414,1)
        pyautogui.click()
        pyautogui.moveTo(845,412,1)
        time.sleep(1)    
        pyautogui.click()
        time.sleep(1)
        for gc in range(0,5):
            time.sleep(58)      
            pyautogui.moveTo(946,286,2)
            pyautogui.click()
        subprocess.Popen("start taskkill /f /im chrome.exe")
        #os.system("start taskkill /f /im chrome.exe")
    except:
        print(" chrome update error")
        
def chrome_update_move():
    print("업데이트 강제클릭")    
    pyautogui.moveTo(1174,60,2)
    time.sleep(1)
    pyautogui.click()
    time.sleep(1)
    pyautogui.moveTo(948,423,1)
    pyautogui.click()
    pyautogui.moveTo(843,422,1)
    time.sleep(1)    
    pyautogui.click()
    time.sleep(120)      
    pyautogui.moveTo(882,296,2)
    pyautogui.click()  
    time.sleep(5)

def close_new_tab(driver):
    tabs = driver.window_handles    
    while len(tabs) != 1:
        driver.switch_to.window(tabs[1])
        driver.close()
        print("close tab")
        tabs = driver.window_handles
        driver.switch_to.window(tabs[0])      



def ftp_pyfile_upload(ver):
    pver = ver.replace('.','')
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(" 파이썬 파일 "+str(pver)+" 업로드 시작")
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/a/proxy.py"    
    print(mtypepath)
    
    ###############
    # 파일 업로드
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            #ftp.encoding = 'utf-8'
            ftp.encoding = 'euckr'
            s = ftp.login(user=user,passwd=passwd)
            ftp.cwd('/proxy_type/')  # 현재 폴더 이동
            print(ftp.dir())
            # 파일업로드
            with open(file=mtypepath, mode='rb') as wf:
                ftp.storbinary('STOR proxy.py' , wf)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print( "FTP 파이썬 파일 업로드 에러")
        
    print( " 버전 "+str(pver)+" 업로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")
    

def ftp_pyfile_download():    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(" 파이썬 파일 다운로드 시작 ")
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/a/proxy.py"
     
    ###############
    # 파일 다운로드 
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            #ftp.encoding = 'utf-8'
            ftp.encoding = 'euckr'
            s = ftp.login(user=user,passwd=passwd)

            ftp.cwd('/proxy_type/')  # 현재 폴더 이동                        
            with open(file=mtypepath, mode='wb') as wf:
                ftp.retrbinary('RETR proxy.py' , wf.write)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print("FTP 파이썬 파일 다운로드 에러")
        
    print( " 파이썬파일  다운로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")






undetec_del()
#temp_del()

#재부팅시간 기본값 부여
reboot_a = 86400
reboot_b = 86400
try:
    rebootset = requests.get("https://www.pwa.pe.kr/server/reboottime.php?x=sdljf9202lk23kjflsjd").json()   
    if rebootset['a'] and rebootset['b']:
        reboot_a = int(rebootset['a'])
        reboot_b = int(rebootset['b'])
except:
    pass

reboottime = reboot_a + randrange(reboot_b) # 15hour ~ 24 hour
print("이 컴퓨터는 "+ str(reboottime) + " 초 뒤에 재부팅합니다.")
os.system('shutdown -r -t '+str(reboottime))

APP_NAME = 'Proxy'
APP_VERSION = '1.0.10.59'

com_name = socket.gethostname()
try:
    #now_chrome_version = listdir('C:/Program Files (x86)/Google/Chrome/Application')[0]
    now_chrome_version = listdir('C:/Program Files/Google/Chrome/Application')[0]
    print(now_chrome_version)
    
except Exception as e:
    print(e)
    print("chrome version error")


autostart = ""

idsetting = "off"
ipsetting = "off"
ibsetting = "off"


# 입력을 받는 비동기 함수
async def get_input():
    user_input = await asyncio.wait_for(loop.run_in_executor(None, input, "수동모드는 10초 안에 아무 값이나 입력하세요: "), timeout=10)
    return user_input

# 메인 함수
async def main():
    global autostart 
    try:
        # 입력을 받거나 타임아웃까지 대기
        user_input = await get_input()
        print("입력값:", user_input)
        
    except asyncio.TimeoutError:
        print("입력이 없어 자동으로 실행됩니다.")
        autostart = "auto"
        



# asyncio 이벤트 루프 생성
loop = asyncio.get_event_loop()

# main 함수 실행
loop.run_until_complete(main())

# 이벤트 루프 종료
loop.close()

weeksetting = ""



if autostart == "auto":
    idsetting = "on"
    ipsetting = "on"
    ibsetting = "on"    
    pass


elif len(sys.argv) > 1 :
    if sys.argv[1] == "auto":
        idsetting = "on"
        ipsetting = "on"
        ibsetting = "on"
        pass
    
else:
    while True:
        weeksetting_ = input("0/1/2/3 입력 ,  엔터는 서버 셋팅주차로 실행 ")
        if weeksetting_ == "0" or weeksetting_ == "1" or weeksetting_ == "2" or weeksetting_ == "3":
            weeksetting = weeksetting_
            print(weeksetting)
            break
        elif weeksetting_ == "":
            break
        
        

    print("")
    
    while True:
        idsetting_ = input("1.아이디 작동  2. 아이디 무시 ,  엔터는 기본실행 ")
        if idsetting_ == "1":
            idsetting = "on"
            break
        elif idsetting_ == "2":
            idsetting = "off"
            break
        elif idsetting_ =="":
            idsetting = "on"
            ipsetting = "on"
            ibsetting = "on"
            break

    print("")


    while True:
        if ipsetting == "on":
            break
        ipsetting_ = input("1.아이피작동  2. 아이피 무시 ")
        if ipsetting_ == "1":
            ipsetting = "on"
            break
        elif ipsetting_ == "2":
            ipsetting = "off"
            break


    print("")


    while True:
        if ibsetting == "on":
            break    
        ibsetting_ = input("1정상 작동  2. 빠른통과 ")
        if ibsetting_ == "1":
            ibsetting = "on"
            break
        elif ibsetting_ == "2":
            ibsetting = "off"
            break
  

while True:
    if autostart == "auto":
        if com_name != "pc001" :
            ftp_pyfile_download()
            break
        break
    
    else:
        if com_name == "pc001" :
            ftpsync = input("파이썬파일 서버로 업로드는 1번  아니면 엔터 : ")
        else:
            ftpsync = input("파이썬파일 다운로드는 1번)  아니면 엔터 : ")
                
        if ftpsync == "":
            break
        else:
            if com_name == "pc001" and str(ftpsync) == "1":
                ftp_pyfile_upload(APP_VERSION)
            else:   
                ftp_pyfile_download()
            break
        
print(" | .. | ")



try:
        
    print(APP_NAME + " " + APP_VERSION + " " +com_name )
    #find current user desktop path
    desktop_path = os.getenv('USERPROFILE')+'/Desktop/a'
    if os.path.isfile(desktop_path+"/proxy_.exe"):
        os.remove(desktop_path+"/proxy_.exe")
        pass
        
    app_version =  requests.get("http://61.109.34.245/proxy/?computer="+com_name+"&version="+APP_VERSION+"&chromeversion="+now_chrome_version).json()
    print("http://61.109.34.245/proxy/?computer="+com_name+"&version="+APP_VERSION+"&chromeversion="+now_chrome_version);
    if app_version['update'] == 'updatexxxxxxx':
        old_app_file = os.path.join(desktop_path,"proxy.exe")
        back_app_file = os.path.join(desktop_path,"proxy_.exe")
        os.rename(old_app_file,back_app_file)
        response = requests.get('http://61.109.34.245/proxy/proxy.exe')
        with open(desktop_path+"/proxy.exe", 'wb') as f:
            f.write(response.content)
        print('update finish ..',end=" ")
        time.sleep(2)
        os.system('shutdown -a')
        print('proxy restart')
        time.sleep(2)
        os.system('shutdown -r -t 5')
        #os.execl(sys.executable, sys.executable, *sys.argv)
        #sys.stdout.flush()
        #os.execv(sys.argv[0], sys.argv)

    elif app_version['update']:
        print("Now APP version " + app_version['update'])


    if os.path.isfile(desktop_path+"/proxy.exe") and os.path.isfile(desktop_path+"/proxy_.exe"):
        os.remove(desktop_path+"/proxy_.exe")
        pass

except:
    print("proxy.exe update fail")

time.sleep(5)

try:    
    if app_version['chrome'] == 'update' or app_version['chrome'] == 'refresh':
        print("크롬 브라우저 업데이트 합니다")
        try:
            #subprocess.Popen('"C:/Program Files/Google/Chrome/Application/chrome.exe" --start-maximized') # (x86)
            #subprocess.call(['start', 'chrome', '--start-maximized'], shell=True)
            os.system("start chrome --start-maximized")
        except Exception as e:
            print(e)
            print("크롬열기오류")
        time.sleep(5)
        pyautogui.moveTo(1890,50,2)        
        pyautogui.click()
        time.sleep(1)
        pyautogui.moveTo(1668,422,2)
        pyautogui.click()
        time.sleep(1)
        pyautogui.moveTo(85,750,2)
        time.sleep(1)    
        pyautogui.click()
        time.sleep(120)
        for gc in range(0,2):
            time.sleep(10)      
            pyautogui.moveTo(1225,294,2)
            pyautogui.click()  
        os.system("start taskkill /f /im chrome.exe")

        try:
            os.system('shutdown -a')
        except:
            pass
        time.sleep(1)
        try:
            os.system('shutdown -r -t '+str(60))
            time.sleep(60)
        except:
            pass

        
        
except Exception as e:
    print(e)
    print("크롬 업데이트 에러")




try:
    threads = []
    while True:
        try:
            initset = requests.get("http://61.109.34.245/proxyserver/?proxy=0.0.0.0&com="+com_name+"&usr=God").json()
            interval = int(initset['interval'])            
            distime = int(initset['delaytime'])
            browsercnt = int(initset['browsercnt'])
            if weeksetting:
                weekpath = int(weeksetting)
            else:
                weekpath = int(initset['weekpath'])
                
            print(str(weekpath)+" 주차 브라우저 셋팅 수 : "+str(browsercnt))
            
            break
        except Exception as e:
            print(e)
            print("서버 응답 업음 .. 20분뒤 재 시도 ")

        time.sleep(1200)

    if len(sys.argv) > 1:
        job(sys.argv[1],idsetting,ipsetting,ibsetting,weekpath)
        pass
    else:
        for number in range(browsercnt):        #browsercnt
            threads.append(threading.Thread(target=job, args=[number,idsetting,ipsetting,ibsetting,weekpath]))
        intx = 0
        for thread in threads:
            thread.start()
            
            if intx>=0:
                if ibsetting == "on":
                    interval += distime
                else:
                    interval = 20
                print("시간간격 : "+str(interval)+ "초 : ",end=" ")            
                time.sleep(int(interval))
            intx +=1
            
        for thread in threads:
            thread.join()

except Exception as e:
    print(e) 
