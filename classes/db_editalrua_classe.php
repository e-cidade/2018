<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: contrib
//CLASSE DA ENTIDADE editalrua
class cl_editalrua { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $d02_contri = 0; 
   var $d02_codedi = 0; 
   var $d02_codigo = 0; 
   var $d02_dtauto_dia = null; 
   var $d02_dtauto_mes = null; 
   var $d02_dtauto_ano = null; 
   var $d02_dtauto = null; 
   var $d02_autori = 'f'; 
   var $d02_idlog = 0; 
   var $d02_data_dia = null; 
   var $d02_data_mes = null; 
   var $d02_data_ano = null; 
   var $d02_data = null; 
   var $d02_profun = 0; 
   var $d02_valorizacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d02_contri = int4 = Contribuição 
                 d02_codedi = int4 = Codigo Edital 
                 d02_codigo = int4 = Rua/Avenida 
                 d02_dtauto = date = Data Autorizacao 
                 d02_autori = bool = Autorizado 
                 d02_idlog = int4 = Codigo do Usuario 
                 d02_data = date = Data de inclusao 
                 d02_profun = float8 = Profundidade 
                 d02_valorizacao = float8 = Percentual de valorizacao 
                 ";
   //funcao construtor da classe 
   function cl_editalrua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("editalrua"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->d02_contri = ($this->d02_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_contri"]:$this->d02_contri);
       $this->d02_codedi = ($this->d02_codedi == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_codedi"]:$this->d02_codedi);
       $this->d02_codigo = ($this->d02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_codigo"]:$this->d02_codigo);
       if($this->d02_dtauto == ""){
         $this->d02_dtauto_dia = ($this->d02_dtauto_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_dtauto_dia"]:$this->d02_dtauto_dia);
         $this->d02_dtauto_mes = ($this->d02_dtauto_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_dtauto_mes"]:$this->d02_dtauto_mes);
         $this->d02_dtauto_ano = ($this->d02_dtauto_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_dtauto_ano"]:$this->d02_dtauto_ano);
         if($this->d02_dtauto_dia != ""){
            $this->d02_dtauto = $this->d02_dtauto_ano."-".$this->d02_dtauto_mes."-".$this->d02_dtauto_dia;
         }
       }
       $this->d02_autori = ($this->d02_autori == "f"?@$GLOBALS["HTTP_POST_VARS"]["d02_autori"]:$this->d02_autori);
       $this->d02_idlog = ($this->d02_idlog == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_idlog"]:$this->d02_idlog);
       if($this->d02_data == ""){
         $this->d02_data_dia = ($this->d02_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_data_dia"]:$this->d02_data_dia);
         $this->d02_data_mes = ($this->d02_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_data_mes"]:$this->d02_data_mes);
         $this->d02_data_ano = ($this->d02_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_data_ano"]:$this->d02_data_ano);
         if($this->d02_data_dia != ""){
            $this->d02_data = $this->d02_data_ano."-".$this->d02_data_mes."-".$this->d02_data_dia;
         }
       }
       $this->d02_profun = ($this->d02_profun == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_profun"]:$this->d02_profun);
       $this->d02_valorizacao = ($this->d02_valorizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_valorizacao"]:$this->d02_valorizacao);
     }else{
       $this->d02_contri = ($this->d02_contri == ""?@$GLOBALS["HTTP_POST_VARS"]["d02_contri"]:$this->d02_contri);
     }
   }
   // funcao para inclusao
   function incluir ($d02_contri){ 
      $this->atualizacampos();
     if($this->d02_codedi == null ){ 
       $this->erro_sql = " Campo Codigo Edital nao Informado.";
       $this->erro_campo = "d02_codedi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_codigo == null ){ 
       $this->erro_sql = " Campo Rua/Avenida nao Informado.";
       $this->erro_campo = "d02_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_dtauto == null ){ 
       $this->d02_dtauto = "null";
     }
     if($this->d02_autori == null ){ 
       $this->erro_sql = " Campo Autorizado nao Informado.";
       $this->erro_campo = "d02_autori";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_idlog == null ){ 
       $this->erro_sql = " Campo Codigo do Usuario nao Informado.";
       $this->erro_campo = "d02_idlog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_data == null ){ 
       $this->erro_sql = " Campo Data de inclusao nao Informado.";
       $this->erro_campo = "d02_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_profun == null ){ 
       $this->erro_sql = " Campo Profundidade nao Informado.";
       $this->erro_campo = "d02_profun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d02_valorizacao == null ){ 
       $this->erro_sql = " Campo Percentual de valorizacao nao Informado.";
       $this->erro_campo = "d02_valorizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d02_contri == "" || $d02_contri == null ){
       $result = db_query("select nextval('editalrua_d02_contri_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: editalrua_d02_contri_seq do campo: d02_contri"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d02_contri = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from editalrua_d02_contri_seq");
       if(($result != false) && (pg_result($result,0,0) < $d02_contri)){
         $this->erro_sql = " Campo d02_contri maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d02_contri = $d02_contri; 
       }
     }
     if(($this->d02_contri == null) || ($this->d02_contri == "") ){ 
       $this->erro_sql = " Campo d02_contri nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into editalrua(
                                       d02_contri 
                                      ,d02_codedi 
                                      ,d02_codigo 
                                      ,d02_dtauto 
                                      ,d02_autori 
                                      ,d02_idlog 
                                      ,d02_data 
                                      ,d02_profun 
                                      ,d02_valorizacao 
                       )
                values (
                                $this->d02_contri 
                               ,$this->d02_codedi 
                               ,$this->d02_codigo 
                               ,".($this->d02_dtauto == "null" || $this->d02_dtauto == ""?"null":"'".$this->d02_dtauto."'")." 
                               ,'$this->d02_autori' 
                               ,$this->d02_idlog 
                               ,".($this->d02_data == "null" || $this->d02_data == ""?"null":"'".$this->d02_data."'")." 
                               ,$this->d02_profun 
                               ,$this->d02_valorizacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->d02_contri) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->d02_contri) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d02_contri;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d02_contri));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,686,'$this->d02_contri','I')");
       $resac = db_query("insert into db_acount values($acount,127,686,'','".AddSlashes(pg_result($resaco,0,'d02_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,687,'','".AddSlashes(pg_result($resaco,0,'d02_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,688,'','".AddSlashes(pg_result($resaco,0,'d02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,689,'','".AddSlashes(pg_result($resaco,0,'d02_dtauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,690,'','".AddSlashes(pg_result($resaco,0,'d02_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,691,'','".AddSlashes(pg_result($resaco,0,'d02_idlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,692,'','".AddSlashes(pg_result($resaco,0,'d02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,4769,'','".AddSlashes(pg_result($resaco,0,'d02_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,127,9445,'','".AddSlashes(pg_result($resaco,0,'d02_valorizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d02_contri=null) { 
      $this->atualizacampos();
     $sql = " update editalrua set ";
     $virgula = "";
     if(trim($this->d02_contri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_contri"])){ 
       $sql  .= $virgula." d02_contri = $this->d02_contri ";
       $virgula = ",";
       if(trim($this->d02_contri) == null ){ 
         $this->erro_sql = " Campo Contribuição nao Informado.";
         $this->erro_campo = "d02_contri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_codedi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_codedi"])){ 
       $sql  .= $virgula." d02_codedi = $this->d02_codedi ";
       $virgula = ",";
       if(trim($this->d02_codedi) == null ){ 
         $this->erro_sql = " Campo Codigo Edital nao Informado.";
         $this->erro_campo = "d02_codedi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_codigo"])){ 
       $sql  .= $virgula." d02_codigo = $this->d02_codigo ";
       $virgula = ",";
       if(trim($this->d02_codigo) == null ){ 
         $this->erro_sql = " Campo Rua/Avenida nao Informado.";
         $this->erro_campo = "d02_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_dtauto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_dtauto_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d02_dtauto_dia"] !="") ){ 
       $sql  .= $virgula." d02_dtauto = '$this->d02_dtauto' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d02_dtauto_dia"])){ 
         $sql  .= $virgula." d02_dtauto = null ";
         $virgula = ",";
       }
     }
     if(trim($this->d02_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_autori"])){ 
       $sql  .= $virgula." d02_autori = '$this->d02_autori' ";
       $virgula = ",";
       if(trim($this->d02_autori) == null ){ 
         $this->erro_sql = " Campo Autorizado nao Informado.";
         $this->erro_campo = "d02_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_idlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_idlog"])){ 
       $sql  .= $virgula." d02_idlog = $this->d02_idlog ";
       $virgula = ",";
       if(trim($this->d02_idlog) == null ){ 
         $this->erro_sql = " Campo Codigo do Usuario nao Informado.";
         $this->erro_campo = "d02_idlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d02_data_dia"] !="") ){ 
       $sql  .= $virgula." d02_data = '$this->d02_data' ";
       $virgula = ",";
       if(trim($this->d02_data) == null ){ 
         $this->erro_sql = " Campo Data de inclusao nao Informado.";
         $this->erro_campo = "d02_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d02_data_dia"])){ 
         $sql  .= $virgula." d02_data = null ";
         $virgula = ",";
         if(trim($this->d02_data) == null ){ 
           $this->erro_sql = " Campo Data de inclusao nao Informado.";
           $this->erro_campo = "d02_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d02_profun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_profun"])){ 
       $sql  .= $virgula." d02_profun = $this->d02_profun ";
       $virgula = ",";
       if(trim($this->d02_profun) == null ){ 
         $this->erro_sql = " Campo Profundidade nao Informado.";
         $this->erro_campo = "d02_profun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d02_valorizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d02_valorizacao"])){ 
       $sql  .= $virgula." d02_valorizacao = $this->d02_valorizacao ";
       $virgula = ",";
       if(trim($this->d02_valorizacao) == null ){ 
         $this->erro_sql = " Campo Percentual de valorizacao nao Informado.";
         $this->erro_campo = "d02_valorizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d02_contri!=null){
       $sql .= " d02_contri = $this->d02_contri";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d02_contri));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,686,'$this->d02_contri','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_contri"]))
           $resac = db_query("insert into db_acount values($acount,127,686,'".AddSlashes(pg_result($resaco,$conresaco,'d02_contri'))."','$this->d02_contri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_codedi"]))
           $resac = db_query("insert into db_acount values($acount,127,687,'".AddSlashes(pg_result($resaco,$conresaco,'d02_codedi'))."','$this->d02_codedi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_codigo"]))
           $resac = db_query("insert into db_acount values($acount,127,688,'".AddSlashes(pg_result($resaco,$conresaco,'d02_codigo'))."','$this->d02_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_dtauto"]))
           $resac = db_query("insert into db_acount values($acount,127,689,'".AddSlashes(pg_result($resaco,$conresaco,'d02_dtauto'))."','$this->d02_dtauto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_autori"]))
           $resac = db_query("insert into db_acount values($acount,127,690,'".AddSlashes(pg_result($resaco,$conresaco,'d02_autori'))."','$this->d02_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_idlog"]))
           $resac = db_query("insert into db_acount values($acount,127,691,'".AddSlashes(pg_result($resaco,$conresaco,'d02_idlog'))."','$this->d02_idlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_data"]))
           $resac = db_query("insert into db_acount values($acount,127,692,'".AddSlashes(pg_result($resaco,$conresaco,'d02_data'))."','$this->d02_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_profun"]))
           $resac = db_query("insert into db_acount values($acount,127,4769,'".AddSlashes(pg_result($resaco,$conresaco,'d02_profun'))."','$this->d02_profun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d02_valorizacao"]))
           $resac = db_query("insert into db_acount values($acount,127,9445,'".AddSlashes(pg_result($resaco,$conresaco,'d02_valorizacao'))."','$this->d02_valorizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d02_contri;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d02_contri;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d02_contri;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d02_contri=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d02_contri));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,686,'$d02_contri','E')");
         $resac = db_query("insert into db_acount values($acount,127,686,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_contri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,687,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_codedi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,688,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,689,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_dtauto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,690,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,691,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_idlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,692,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,4769,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,127,9445,'','".AddSlashes(pg_result($resaco,$iresaco,'d02_valorizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from editalrua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d02_contri != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d02_contri = $d02_contri ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d02_contri;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d02_contri;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d02_contri;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:editalrua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d02_contri=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from editalrua ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = editalrua.d02_codigo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = editalrua.d02_idlog";
     $sql .= "      inner join edital  on  edital.d01_codedi = editalrua.d02_codedi";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = edital.d01_receit";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = edital.d01_idlog";
     $sql2 = "";
     if($dbwhere==""){
       if($d02_contri!=null ){
         $sql2 .= " where editalrua.d02_contri = $d02_contri "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $d02_contri=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from editalrua ";
     $sql2 = "";
     if($dbwhere==""){
       if($d02_contri!=null ){
         $sql2 .= " where editalrua.d02_contri = $d02_contri "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>