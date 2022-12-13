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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptupadraoconstrarea
class cl_iptupadraoconstrarea { 
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
   var $j116_sequencial = 0; 
   var $j116_iptupadraoconstr = 0; 
   var $j116_areaini = 0; 
   var $j116_areafim = 0; 
   var $j116_caracter = 0; 
   var $j116_peso = 0; 
   var $j116_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j116_sequencial = int4 = Sequencial 
                 j116_iptupadraoconstr = int4 = Iptu Padrão Construção 
                 j116_areaini = float8 = Área Inicial 
                 j116_areafim = float8 = Área Final 
                 j116_caracter = int4 = Caracter 
                 j116_peso = float8 = Peso 
                 j116_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_iptupadraoconstrarea() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptupadraoconstrarea"); 
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
       $this->j116_sequencial = ($this->j116_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_sequencial"]:$this->j116_sequencial);
       $this->j116_iptupadraoconstr = ($this->j116_iptupadraoconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_iptupadraoconstr"]:$this->j116_iptupadraoconstr);
       $this->j116_areaini = ($this->j116_areaini == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_areaini"]:$this->j116_areaini);
       $this->j116_areafim = ($this->j116_areafim == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_areafim"]:$this->j116_areafim);
       $this->j116_caracter = ($this->j116_caracter == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_caracter"]:$this->j116_caracter);
       $this->j116_peso = ($this->j116_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_peso"]:$this->j116_peso);
       $this->j116_anousu = ($this->j116_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_anousu"]:$this->j116_anousu);
     }else{
       $this->j116_sequencial = ($this->j116_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j116_sequencial"]:$this->j116_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j116_sequencial){ 
      $this->atualizacampos();
     if($this->j116_iptupadraoconstr == null ){ 
       $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
       $this->erro_campo = "j116_iptupadraoconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j116_areaini == null ){ 
       $this->erro_sql = " Campo Área Inicial nao Informado.";
       $this->erro_campo = "j116_areaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j116_areafim == null ){ 
       $this->erro_sql = " Campo Área Final nao Informado.";
       $this->erro_campo = "j116_areafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j116_caracter == null ){ 
       $this->erro_sql = " Campo Caracter nao Informado.";
       $this->erro_campo = "j116_caracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j116_peso == null ){ 
       $this->erro_sql = " Campo Peso nao Informado.";
       $this->erro_campo = "j116_peso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j116_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j116_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j116_sequencial == "" || $j116_sequencial == null ){
       $result = db_query("select nextval('iptupadraoconstrarea_j116_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptupadraoconstrarea_j116_sequencial_seq do campo: j116_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j116_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptupadraoconstrarea_j116_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j116_sequencial)){
         $this->erro_sql = " Campo j116_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j116_sequencial = $j116_sequencial; 
       }
     }
     if(($this->j116_sequencial == null) || ($this->j116_sequencial == "") ){ 
       $this->erro_sql = " Campo j116_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptupadraoconstrarea(
                                       j116_sequencial 
                                      ,j116_iptupadraoconstr 
                                      ,j116_areaini 
                                      ,j116_areafim 
                                      ,j116_caracter 
                                      ,j116_peso 
                                      ,j116_anousu 
                       )
                values (
                                $this->j116_sequencial 
                               ,$this->j116_iptupadraoconstr 
                               ,$this->j116_areaini 
                               ,$this->j116_areafim 
                               ,$this->j116_caracter 
                               ,$this->j116_peso 
                               ,$this->j116_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Construção Padrao Área ($this->j116_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Construção Padrao Área já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Construção Padrao Área ($this->j116_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j116_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j116_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15160,'$this->j116_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2670,15160,'','".AddSlashes(pg_result($resaco,0,'j116_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15161,'','".AddSlashes(pg_result($resaco,0,'j116_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15162,'','".AddSlashes(pg_result($resaco,0,'j116_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15163,'','".AddSlashes(pg_result($resaco,0,'j116_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15164,'','".AddSlashes(pg_result($resaco,0,'j116_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15166,'','".AddSlashes(pg_result($resaco,0,'j116_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2670,15167,'','".AddSlashes(pg_result($resaco,0,'j116_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j116_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptupadraoconstrarea set ";
     $virgula = "";
     if(trim($this->j116_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_sequencial"])){ 
       $sql  .= $virgula." j116_sequencial = $this->j116_sequencial ";
       $virgula = ",";
       if(trim($this->j116_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j116_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_iptupadraoconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_iptupadraoconstr"])){ 
       $sql  .= $virgula." j116_iptupadraoconstr = $this->j116_iptupadraoconstr ";
       $virgula = ",";
       if(trim($this->j116_iptupadraoconstr) == null ){ 
         $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
         $this->erro_campo = "j116_iptupadraoconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_areaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_areaini"])){ 
       $sql  .= $virgula." j116_areaini = $this->j116_areaini ";
       $virgula = ",";
       if(trim($this->j116_areaini) == null ){ 
         $this->erro_sql = " Campo Área Inicial nao Informado.";
         $this->erro_campo = "j116_areaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_areafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_areafim"])){ 
       $sql  .= $virgula." j116_areafim = $this->j116_areafim ";
       $virgula = ",";
       if(trim($this->j116_areafim) == null ){ 
         $this->erro_sql = " Campo Área Final nao Informado.";
         $this->erro_campo = "j116_areafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_caracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_caracter"])){ 
       $sql  .= $virgula." j116_caracter = $this->j116_caracter ";
       $virgula = ",";
       if(trim($this->j116_caracter) == null ){ 
         $this->erro_sql = " Campo Caracter nao Informado.";
         $this->erro_campo = "j116_caracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_peso"])){ 
       $sql  .= $virgula." j116_peso = $this->j116_peso ";
       $virgula = ",";
       if(trim($this->j116_peso) == null ){ 
         $this->erro_sql = " Campo Peso nao Informado.";
         $this->erro_campo = "j116_peso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j116_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j116_anousu"])){ 
       $sql  .= $virgula." j116_anousu = $this->j116_anousu ";
       $virgula = ",";
       if(trim($this->j116_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j116_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j116_sequencial!=null){
       $sql .= " j116_sequencial = $this->j116_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j116_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15160,'$this->j116_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_sequencial"]) || $this->j116_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2670,15160,'".AddSlashes(pg_result($resaco,$conresaco,'j116_sequencial'))."','$this->j116_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_iptupadraoconstr"]) || $this->j116_iptupadraoconstr != "")
           $resac = db_query("insert into db_acount values($acount,2670,15161,'".AddSlashes(pg_result($resaco,$conresaco,'j116_iptupadraoconstr'))."','$this->j116_iptupadraoconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_areaini"]) || $this->j116_areaini != "")
           $resac = db_query("insert into db_acount values($acount,2670,15162,'".AddSlashes(pg_result($resaco,$conresaco,'j116_areaini'))."','$this->j116_areaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_areafim"]) || $this->j116_areafim != "")
           $resac = db_query("insert into db_acount values($acount,2670,15163,'".AddSlashes(pg_result($resaco,$conresaco,'j116_areafim'))."','$this->j116_areafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_caracter"]) || $this->j116_caracter != "")
           $resac = db_query("insert into db_acount values($acount,2670,15164,'".AddSlashes(pg_result($resaco,$conresaco,'j116_caracter'))."','$this->j116_caracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_peso"]) || $this->j116_peso != "")
           $resac = db_query("insert into db_acount values($acount,2670,15166,'".AddSlashes(pg_result($resaco,$conresaco,'j116_peso'))."','$this->j116_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j116_anousu"]) || $this->j116_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2670,15167,'".AddSlashes(pg_result($resaco,$conresaco,'j116_anousu'))."','$this->j116_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construção Padrao Área nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j116_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construção Padrao Área nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j116_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j116_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15160,'$j116_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2670,15160,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15161,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15162,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15163,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15164,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15166,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2670,15167,'','".AddSlashes(pg_result($resaco,$iresaco,'j116_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptupadraoconstrarea
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j116_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j116_sequencial = $j116_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construção Padrao Área nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j116_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construção Padrao Área nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j116_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j116_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptupadraoconstrarea";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j116_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrarea ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = iptupadraoconstrarea.j116_caracter";
     $sql .= "      inner join iptupadraoconstr  on  iptupadraoconstr.j115_sequencial = iptupadraoconstrarea.j116_iptupadraoconstr";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j116_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrarea.j116_sequencial = $j116_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $j116_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrarea ";
     $sql2 = "";
     if($dbwhere==""){
       if($j116_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrarea.j116_sequencial = $j116_sequencial "; 
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