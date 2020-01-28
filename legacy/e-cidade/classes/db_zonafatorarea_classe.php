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
//CLASSE DA ENTIDADE zonafatorarea
class cl_zonafatorarea { 
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
   var $j113_sequencial = 0; 
   var $j113_zona = 0; 
   var $j113_areaini = 0; 
   var $j113_areafim = 0; 
   var $j113_fator = 0; 
   var $j113_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j113_sequencial = int4 = Sequencial 
                 j113_zona = int4 = Zona 
                 j113_areaini = float8 = Area Inicial 
                 j113_areafim = float8 = Area Final 
                 j113_fator = float8 = Fator 
                 j113_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_zonafatorarea() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("zonafatorarea"); 
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
       $this->j113_sequencial = ($this->j113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_sequencial"]:$this->j113_sequencial);
       $this->j113_zona = ($this->j113_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_zona"]:$this->j113_zona);
       $this->j113_areaini = ($this->j113_areaini == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_areaini"]:$this->j113_areaini);
       $this->j113_areafim = ($this->j113_areafim == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_areafim"]:$this->j113_areafim);
       $this->j113_fator = ($this->j113_fator == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_fator"]:$this->j113_fator);
       $this->j113_anousu = ($this->j113_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_anousu"]:$this->j113_anousu);
     }else{
       $this->j113_sequencial = ($this->j113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j113_sequencial"]:$this->j113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j113_sequencial){ 
      $this->atualizacampos();
     if($this->j113_zona == null ){ 
       $this->erro_sql = " Campo Zona nao Informado.";
       $this->erro_campo = "j113_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j113_areaini == null ){ 
       $this->erro_sql = " Campo Area Inicial nao Informado.";
       $this->erro_campo = "j113_areaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j113_areafim == null ){ 
       $this->erro_sql = " Campo Area Final nao Informado.";
       $this->erro_campo = "j113_areafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j113_fator == null ){ 
       $this->erro_sql = " Campo Fator nao Informado.";
       $this->erro_campo = "j113_fator";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j113_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j113_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j113_sequencial == "" || $j113_sequencial == null ){
       $result = db_query("select nextval('zonafatorarea_j113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: zonafatorarea_j113_sequencial_seq do campo: j113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from zonafatorarea_j113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j113_sequencial)){
         $this->erro_sql = " Campo j113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j113_sequencial = $j113_sequencial; 
       }
     }
     if(($this->j113_sequencial == null) || ($this->j113_sequencial == "") ){ 
       $this->erro_sql = " Campo j113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into zonafatorarea(
                                       j113_sequencial 
                                      ,j113_zona 
                                      ,j113_areaini 
                                      ,j113_areafim 
                                      ,j113_fator 
                                      ,j113_anousu 
                       )
                values (
                                $this->j113_sequencial 
                               ,$this->j113_zona 
                               ,$this->j113_areaini 
                               ,$this->j113_areafim 
                               ,$this->j113_fator 
                               ,$this->j113_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Zona Fator Area ($this->j113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Zona Fator Area já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Zona Fator Area ($this->j113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15097,'$this->j113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2655,15097,'','".AddSlashes(pg_result($resaco,0,'j113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2655,15098,'','".AddSlashes(pg_result($resaco,0,'j113_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2655,15099,'','".AddSlashes(pg_result($resaco,0,'j113_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2655,15100,'','".AddSlashes(pg_result($resaco,0,'j113_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2655,15101,'','".AddSlashes(pg_result($resaco,0,'j113_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2655,15151,'','".AddSlashes(pg_result($resaco,0,'j113_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update zonafatorarea set ";
     $virgula = "";
     if(trim($this->j113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_sequencial"])){ 
       $sql  .= $virgula." j113_sequencial = $this->j113_sequencial ";
       $virgula = ",";
       if(trim($this->j113_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j113_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_zona"])){ 
       $sql  .= $virgula." j113_zona = $this->j113_zona ";
       $virgula = ",";
       if(trim($this->j113_zona) == null ){ 
         $this->erro_sql = " Campo Zona nao Informado.";
         $this->erro_campo = "j113_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j113_areaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_areaini"])){ 
       $sql  .= $virgula." j113_areaini = $this->j113_areaini ";
       $virgula = ",";
       if(trim($this->j113_areaini) == null ){ 
         $this->erro_sql = " Campo Area Inicial nao Informado.";
         $this->erro_campo = "j113_areaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j113_areafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_areafim"])){ 
       $sql  .= $virgula." j113_areafim = $this->j113_areafim ";
       $virgula = ",";
       if(trim($this->j113_areafim) == null ){ 
         $this->erro_sql = " Campo Area Final nao Informado.";
         $this->erro_campo = "j113_areafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j113_fator)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_fator"])){ 
       $sql  .= $virgula." j113_fator = $this->j113_fator ";
       $virgula = ",";
       if(trim($this->j113_fator) == null ){ 
         $this->erro_sql = " Campo Fator nao Informado.";
         $this->erro_campo = "j113_fator";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j113_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j113_anousu"])){ 
       $sql  .= $virgula." j113_anousu = $this->j113_anousu ";
       $virgula = ",";
       if(trim($this->j113_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j113_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j113_sequencial!=null){
       $sql .= " j113_sequencial = $this->j113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15097,'$this->j113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_sequencial"]) || $this->j113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2655,15097,'".AddSlashes(pg_result($resaco,$conresaco,'j113_sequencial'))."','$this->j113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_zona"]) || $this->j113_zona != "")
           $resac = db_query("insert into db_acount values($acount,2655,15098,'".AddSlashes(pg_result($resaco,$conresaco,'j113_zona'))."','$this->j113_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_areaini"]) || $this->j113_areaini != "")
           $resac = db_query("insert into db_acount values($acount,2655,15099,'".AddSlashes(pg_result($resaco,$conresaco,'j113_areaini'))."','$this->j113_areaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_areafim"]) || $this->j113_areafim != "")
           $resac = db_query("insert into db_acount values($acount,2655,15100,'".AddSlashes(pg_result($resaco,$conresaco,'j113_areafim'))."','$this->j113_areafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_fator"]) || $this->j113_fator != "")
           $resac = db_query("insert into db_acount values($acount,2655,15101,'".AddSlashes(pg_result($resaco,$conresaco,'j113_fator'))."','$this->j113_fator',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j113_anousu"]) || $this->j113_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2655,15151,'".AddSlashes(pg_result($resaco,$conresaco,'j113_anousu'))."','$this->j113_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Zona Fator Area nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Zona Fator Area nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15097,'$j113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2655,15097,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2655,15098,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2655,15099,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2655,15100,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2655,15101,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2655,15151,'','".AddSlashes(pg_result($resaco,$iresaco,'j113_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from zonafatorarea
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j113_sequencial = $j113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Zona Fator Area nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Zona Fator Area nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:zonafatorarea";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonafatorarea ";
     $sql .= "      inner join zonas  on  zonas.j50_zona = zonafatorarea.j113_zona";
     $sql2 = "";
     if($dbwhere==""){
       if($j113_sequencial!=null ){
         $sql2 .= " where zonafatorarea.j113_sequencial = $j113_sequencial "; 
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
   function sql_query_file ( $j113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonafatorarea ";
     $sql2 = "";
     if($dbwhere==""){
       if($j113_sequencial!=null ){
         $sql2 .= " where zonafatorarea.j113_sequencial = $j113_sequencial "; 
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