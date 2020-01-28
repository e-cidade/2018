<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: issqn
//CLASSE DA ENTIDADE issmovalvara
class cl_issmovalvara { 
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
   var $q120_sequencial = 0; 
   var $q120_issalvara = 0; 
   var $q120_isstipomovalvara = 0; 
   var $q120_dtmov_dia = null; 
   var $q120_dtmov_mes = null; 
   var $q120_dtmov_ano = null; 
   var $q120_dtmov = null; 
   var $q120_validadealvara = 0; 
   var $q120_usuario = 0; 
   var $q120_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q120_sequencial = int4 = Sequencial 
                 q120_issalvara = int4 = Código do Alvará 
                 q120_isstipomovalvara = int4 = Tipo de Movimentação do Alvará 
                 q120_dtmov = date = Data da Movimentação 
                 q120_validadealvara = int4 = Validade do Alvará 
                 q120_usuario = int4 = Usuario 
                 q120_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_issmovalvara() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issmovalvara"); 
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
       $this->q120_sequencial = ($this->q120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_sequencial"]:$this->q120_sequencial);
       $this->q120_issalvara = ($this->q120_issalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_issalvara"]:$this->q120_issalvara);
       $this->q120_isstipomovalvara = ($this->q120_isstipomovalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_isstipomovalvara"]:$this->q120_isstipomovalvara);
       if($this->q120_dtmov == ""){
         $this->q120_dtmov_dia = ($this->q120_dtmov_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_dtmov_dia"]:$this->q120_dtmov_dia);
         $this->q120_dtmov_mes = ($this->q120_dtmov_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_dtmov_mes"]:$this->q120_dtmov_mes);
         $this->q120_dtmov_ano = ($this->q120_dtmov_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_dtmov_ano"]:$this->q120_dtmov_ano);
         if($this->q120_dtmov_dia != ""){
            $this->q120_dtmov = $this->q120_dtmov_ano."-".$this->q120_dtmov_mes."-".$this->q120_dtmov_dia;
         }
       }
       $this->q120_validadealvara = ($this->q120_validadealvara == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_validadealvara"]:$this->q120_validadealvara);
       $this->q120_usuario = ($this->q120_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_usuario"]:$this->q120_usuario);
       $this->q120_obs = ($this->q120_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_obs"]:$this->q120_obs);
     }else{
       $this->q120_sequencial = ($this->q120_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q120_sequencial"]:$this->q120_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q120_sequencial){ 
      $this->atualizacampos();
     if($this->q120_issalvara == null ){ 
       $this->erro_sql = " Campo Código do Alvará nao Informado.";
       $this->erro_campo = "q120_issalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q120_isstipomovalvara == null ){ 
       $this->erro_sql = " Campo Tipo de Movimentação do Alvará nao Informado.";
       $this->erro_campo = "q120_isstipomovalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q120_dtmov == null ){ 
       $this->erro_sql = " Campo Data da Movimentação nao Informado.";
       $this->erro_campo = "q120_dtmov_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q120_validadealvara == null ){ 
       $this->q120_validadealvara = "0";
     }
     if($this->q120_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "q120_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if($q120_sequencial == "" || $q120_sequencial == null ){
       $result = db_query("select nextval('issmovalvara_q120_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issmovalvara_q120_sequencial_seq do campo: q120_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q120_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issmovalvara_q120_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q120_sequencial)){
         $this->erro_sql = " Campo q120_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q120_sequencial = $q120_sequencial; 
       }
     }
     if(($this->q120_sequencial == null) || ($this->q120_sequencial == "") ){ 
       $this->erro_sql = " Campo q120_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issmovalvara(
                                       q120_sequencial 
                                      ,q120_issalvara 
                                      ,q120_isstipomovalvara 
                                      ,q120_dtmov 
                                      ,q120_validadealvara 
                                      ,q120_usuario 
                                      ,q120_obs 
                       )
                values (
                                $this->q120_sequencial 
                               ,$this->q120_issalvara 
                               ,$this->q120_isstipomovalvara 
                               ,".($this->q120_dtmov == "null" || $this->q120_dtmov == ""?"null":"'".$this->q120_dtmov."'")." 
                               ,$this->q120_validadealvara 
                               ,$this->q120_usuario 
                               ,'$this->q120_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação do Alvará ($this->q120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação do Alvará já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação do Alvará ($this->q120_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q120_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q120_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18334,'$this->q120_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3242,18334,'','".AddSlashes(pg_result($resaco,0,'q120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18335,'','".AddSlashes(pg_result($resaco,0,'q120_issalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18336,'','".AddSlashes(pg_result($resaco,0,'q120_isstipomovalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18337,'','".AddSlashes(pg_result($resaco,0,'q120_dtmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18338,'','".AddSlashes(pg_result($resaco,0,'q120_validadealvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18339,'','".AddSlashes(pg_result($resaco,0,'q120_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3242,18340,'','".AddSlashes(pg_result($resaco,0,'q120_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q120_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issmovalvara set ";
     $virgula = "";
     if(trim($this->q120_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_sequencial"])){ 
       $sql  .= $virgula." q120_sequencial = $this->q120_sequencial ";
       $virgula = ",";
       if(trim($this->q120_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q120_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q120_issalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_issalvara"])){ 
       $sql  .= $virgula." q120_issalvara = $this->q120_issalvara ";
       $virgula = ",";
       if(trim($this->q120_issalvara) == null ){ 
         $this->erro_sql = " Campo Código do Alvará nao Informado.";
         $this->erro_campo = "q120_issalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q120_isstipomovalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_isstipomovalvara"])){ 
       $sql  .= $virgula." q120_isstipomovalvara = $this->q120_isstipomovalvara ";
       $virgula = ",";
       if(trim($this->q120_isstipomovalvara) == null ){ 
         $this->erro_sql = " Campo Tipo de Movimentação do Alvará nao Informado.";
         $this->erro_campo = "q120_isstipomovalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q120_dtmov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_dtmov_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q120_dtmov_dia"] !="") ){ 
       $sql  .= $virgula." q120_dtmov = '$this->q120_dtmov' ";
       $virgula = ",";
       if(trim($this->q120_dtmov) == null ){ 
         $this->erro_sql = " Campo Data da Movimentação nao Informado.";
         $this->erro_campo = "q120_dtmov_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q120_dtmov_dia"])){ 
         $sql  .= $virgula." q120_dtmov = null ";
         $virgula = ",";
         if(trim($this->q120_dtmov) == null ){ 
           $this->erro_sql = " Campo Data da Movimentação nao Informado.";
           $this->erro_campo = "q120_dtmov_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q120_validadealvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_validadealvara"])){ 
        if(trim($this->q120_validadealvara)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q120_validadealvara"])){ 
           $this->q120_validadealvara = "0" ; 
        } 
       $sql  .= $virgula." q120_validadealvara = $this->q120_validadealvara ";
       $virgula = ",";
     }
     if(trim($this->q120_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_usuario"])){ 
       $sql  .= $virgula." q120_usuario = $this->q120_usuario ";
       $virgula = ",";
       if(trim($this->q120_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "q120_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q120_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q120_obs"])){ 
       $sql  .= $virgula." q120_obs = '$this->q120_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q120_sequencial!=null){
       $sql .= " q120_sequencial = $this->q120_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q120_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18334,'$this->q120_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_sequencial"]) || $this->q120_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3242,18334,'".AddSlashes(pg_result($resaco,$conresaco,'q120_sequencial'))."','$this->q120_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_issalvara"]) || $this->q120_issalvara != "")
           $resac = db_query("insert into db_acount values($acount,3242,18335,'".AddSlashes(pg_result($resaco,$conresaco,'q120_issalvara'))."','$this->q120_issalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_isstipomovalvara"]) || $this->q120_isstipomovalvara != "")
           $resac = db_query("insert into db_acount values($acount,3242,18336,'".AddSlashes(pg_result($resaco,$conresaco,'q120_isstipomovalvara'))."','$this->q120_isstipomovalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_dtmov"]) || $this->q120_dtmov != "")
           $resac = db_query("insert into db_acount values($acount,3242,18337,'".AddSlashes(pg_result($resaco,$conresaco,'q120_dtmov'))."','$this->q120_dtmov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_validadealvara"]) || $this->q120_validadealvara != "")
           $resac = db_query("insert into db_acount values($acount,3242,18338,'".AddSlashes(pg_result($resaco,$conresaco,'q120_validadealvara'))."','$this->q120_validadealvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_usuario"]) || $this->q120_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3242,18339,'".AddSlashes(pg_result($resaco,$conresaco,'q120_usuario'))."','$this->q120_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q120_obs"]) || $this->q120_obs != "")
           $resac = db_query("insert into db_acount values($acount,3242,18340,'".AddSlashes(pg_result($resaco,$conresaco,'q120_obs'))."','$this->q120_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação do Alvará nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação do Alvará nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q120_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q120_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18334,'$q120_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3242,18334,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18335,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_issalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18336,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_isstipomovalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18337,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_dtmov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18338,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_validadealvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18339,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3242,18340,'','".AddSlashes(pg_result($resaco,$iresaco,'q120_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issmovalvara
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q120_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q120_sequencial = $q120_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação do Alvará nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q120_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação do Alvará nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q120_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q120_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issmovalvara";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmovalvara ";
     $sql .= "      inner join isstipomovalvara  on  isstipomovalvara.q121_sequencial = issmovalvara.q120_isstipomovalvara";
     $sql .= "      inner join issalvara  on  issalvara.q123_sequencial = issmovalvara.q120_issalvara";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issalvara.q123_inscr";
     $sql .= "      inner join isstipoalvara  on  isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara";
     $sql2 = "";
     if($dbwhere==""){
       if($q120_sequencial!=null ){
         $sql2 .= " where issmovalvara.q120_sequencial = $q120_sequencial "; 
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
   function sql_query_file ( $q120_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issmovalvara ";
     $sql2 = "";
     if($dbwhere==""){
       if($q120_sequencial!=null ){
         $sql2 .= " where issmovalvara.q120_sequencial = $q120_sequencial "; 
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
  
 /** Retorna uma String com todos os alvaras que podem ser transformados
   * @param String $sWhere - q120_sequencial = 4466
   * @return String
   */
  function sql_queryListaAlvaraTransf ($sOrdem=null, $sWhere = null){ 
    
    $dtHoje = date("Y-m-d",db_getsession("DB_datausu"));
    $sSql  = " select max(q120_sequencial) as q120_sequencial,                                                            ";
    $sSql .= "        q120_issalvara, z01_nome,                                                                           ";
    $sSql .= "        q123_inscr,                                                                                         ";
    $sSql .= "        issgrupotipoalvara.q97_sequencial,                                                                  "; 
    $sSql .= "        issgrupotipoalvara.q97_descricao,                                                                   ";
    $sSql .= "        isstipoalvara.q98_sequencial,                                                                       "; 
    $sSql .= "        isstipoalvara.q98_descricao                                                                         ";
    $sSql .= "   from issmovalvara                                                                                        ";
    $sSql .= "  inner join issalvara          on issalvara.q123_sequencial         = issmovalvara.q120_issalvara          ";
    $sSql .= "  inner join isstipoalvara      on isstipoalvara.q98_sequencial      = issalvara.q123_isstipoalvara         ";
    $sSql .= "  inner join issgrupotipoalvara on issgrupotipoalvara.q97_sequencial = isstipoalvara.q98_issgrupotipoalvara ";
    $sSql .= "  inner join issbase            on issbase.q02_inscr                 = issalvara.q123_inscr                 ";
    $sSql .= "  inner join cgm                on cgm.z01_numcgm                    = issbase.q02_numcgm                   ";    
    $sSql .= "   where isstipoalvara.q98_permitetransformacao is true                                                     ";
    $sSql .= "    and case 
                        when issgrupotipoalvara.q97_isstipogrupoalvara = 1 
                          then true 
                        else (issmovalvara.q120_dtmov + issmovalvara.q120_validadealvara) >= '{$dtHoje}' and q02_dtbaix is null end ";
    if ($sWhere != "") {
      $sSql .= " and " .$sWhere;
    }
    
    $sSql .= "group by q120_issalvara,                                                                                    ";
    $sSql .= "         q123_inscr, z01_nome,                                                                              ";
    $sSql .= "         issgrupotipoalvara.q97_sequencial,                                                                 ";
    $sSql .= "         issgrupotipoalvara.q97_descricao,                                                                  ";
    $sSql .= "         isstipoalvara.q98_sequencial,                                                                      ";
    $sSql .= "         isstipoalvara.q98_descricao                                                                        ";
    
    if (!empty($sOrdem)) {
        $sSql .= " order by " .$sOrdem;
    }
    return $sSql;
  }
  
  
  /**
   * Retorna o ultimo registro de uma Movimentação do Alvara e o tipo da movimentação
   *
   * @return String
   */
  function sql_AlvaraLiberado($q123_sequencial=null,$iInscr, $campos="*", $dbwhere="") {
    
    $sql = "select q120_isstipomovalvara,";
    
    if ($campos != "*" ) {
      
       $campos_sql = split("#",$campos);
       $virgula = "";
       for ($i=0;$i<sizeof($campos_sql);$i++) {
         
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }

    $sql .= "  from issmovalvara                                                                                        ";
    $sql .= "       inner join issalvara        on issalvara.q123_sequencial       = issmovalvara.q120_issalvara        ";
    $sql .= "       inner join isstipomovalvara on isstipomovalvara.q121_sequencial = issmovalvara.q120_isstipomovalvara";
    $sql .= " where issalvara.q123_inscr               = {$iInscr}                                                      ";
    $sql .= "   and issmovalvara.q120_isstipomovalvara = 1                                                              ";
    if($dbwhere != "") {
      $sql.= "  and $dbwhere";
    }
    
    $sql .= " order by q120_sequencial ; ";
    
    return $sql;
  }

  /**
   * Retorna os alvaras que podem ser transformados
   *
   * @param string $sCampos
   * @param string $sWhere
   * @access public
   * @return string
   */
  public function sql_queryAlvarasTransformacao($sCampos = "*", $sWhere = "") {

    $sSql  = " select {$sCampos}                                                                                              ";
    $sSql .= "   from issalvara                                                                                               ";
    $sSql .= "        inner join issmovalvara       on q123_sequencial   = q120_issalvara                                     ";
    $sSql .= "        inner join issbase            on issbase.q02_inscr = issalvara.q123_inscr                               ";
    $sSql .= "        inner join cgm                on cgm.z01_numcgm    = issbase.q02_numcgm                                 ";
    $sSql .= "        inner join isstipoalvara      on q123_isstipoalvara = q98_sequencial                                    ";
    $sSql .= "        inner join issgrupotipoalvara on q97_sequencial = q98_issgrupotipoalvara                                ";  
    $sSql .= "  where (q123_sequencial, q120_sequencial) in (select movimentacao.q120_issalvara,                              ";
    $sSql .= "                                                      max(movimentacao.q120_sequencial)                         ";
    $sSql .= "                                                 from issmovalvara as movimentacao                              ";
    $sSql .= "                                                where movimentacao.q120_isstipomovalvara not in (3, 6, 7, 8)    ";
    $sSql .= "                                                  and movimentacao.q120_issalvara = q120_issalvara              ";
    $sSql .= "                                             group by movimentacao.q120_issalvara)                              ";

    if ( !empty($sWhere) ) {
      $sSql .= " and {$sWhere} ";
    }

    $sSql .= " order by q123_sequencial, q120_sequencial ";
    
    return $sSql;
  }
  
}
?>