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

//MODULO: cadastro
//CLASSE DA ENTIDADE ruashistorico
class cl_ruashistorico { 
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
   var $j136_sequencial = 0; 
   var $j136_ruas = 0; 
   var $j136_ruastipo = 0; 
   var $j136_lei = null; 
   var $j136_datalei_dia = null; 
   var $j136_datalei_mes = null; 
   var $j136_datalei_ano = null; 
   var $j136_datalei = null; 
   var $j136_nomeanterior = null; 
   var $j136_dataalteracao_dia = null; 
   var $j136_dataalteracao_mes = null; 
   var $j136_dataalteracao_ano = null; 
   var $j136_dataalteracao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j136_sequencial = int8 = Código 
                 j136_ruas = int4 = Código Logradouro 
                 j136_ruastipo = int4 = Código Tipo 
                 j136_lei = varchar(50) = Lei 
                 j136_datalei = date = Data Lei 
                 j136_nomeanterior = varchar(50) = Nome Anterior 
                 j136_dataalteracao = date = Data Alteração 
                 ";
   //funcao construtor da classe 
   function cl_ruashistorico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ruashistorico"); 
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
       $this->j136_sequencial = ($this->j136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_sequencial"]:$this->j136_sequencial);
       $this->j136_ruas = ($this->j136_ruas == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_ruas"]:$this->j136_ruas);
       $this->j136_ruastipo = ($this->j136_ruastipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_ruastipo"]:$this->j136_ruastipo);
       $this->j136_lei = ($this->j136_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_lei"]:$this->j136_lei);
       if($this->j136_datalei == ""){
         $this->j136_datalei_dia = ($this->j136_datalei_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_datalei_dia"]:$this->j136_datalei_dia);
         $this->j136_datalei_mes = ($this->j136_datalei_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_datalei_mes"]:$this->j136_datalei_mes);
         $this->j136_datalei_ano = ($this->j136_datalei_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_datalei_ano"]:$this->j136_datalei_ano);
         if($this->j136_datalei_dia != ""){
            $this->j136_datalei = $this->j136_datalei_ano."-".$this->j136_datalei_mes."-".$this->j136_datalei_dia;
         }
       }
       $this->j136_nomeanterior = ($this->j136_nomeanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_nomeanterior"]:$this->j136_nomeanterior);
       if($this->j136_dataalteracao == ""){
         $this->j136_dataalteracao_dia = ($this->j136_dataalteracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_dia"]:$this->j136_dataalteracao_dia);
         $this->j136_dataalteracao_mes = ($this->j136_dataalteracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_mes"]:$this->j136_dataalteracao_mes);
         $this->j136_dataalteracao_ano = ($this->j136_dataalteracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_ano"]:$this->j136_dataalteracao_ano);
         if($this->j136_dataalteracao_dia != ""){
            $this->j136_dataalteracao = $this->j136_dataalteracao_ano."-".$this->j136_dataalteracao_mes."-".$this->j136_dataalteracao_dia;
         }
       }
     }else{
       $this->j136_sequencial = ($this->j136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j136_sequencial"]:$this->j136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j136_sequencial){ 
      $this->atualizacampos();
     if($this->j136_ruas == null ){ 
       $this->erro_sql = " Campo Código Logradouro nao Informado.";
       $this->erro_campo = "j136_ruas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j136_ruastipo == null ){ 
       $this->erro_sql = " Campo Código Tipo nao Informado.";
       $this->erro_campo = "j136_ruastipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j136_datalei == null ){ 
       $this->j136_datalei = "null";
     }
     if($this->j136_nomeanterior == null ){ 
       $this->erro_sql = " Campo Nome Anterior nao Informado.";
       $this->erro_campo = "j136_nomeanterior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j136_dataalteracao == null ){ 
       $this->erro_sql = " Campo Data Alteração nao Informado.";
       $this->erro_campo = "j136_dataalteracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j136_sequencial == "" || $j136_sequencial == null ){
       $result = db_query("select nextval('ruashistorico_j136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ruashistorico_j136_sequencial_seq do campo: j136_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ruashistorico_j136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j136_sequencial)){
         $this->erro_sql = " Campo j136_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j136_sequencial = $j136_sequencial; 
       }
     }
     if(($this->j136_sequencial == null) || ($this->j136_sequencial == "") ){ 
       $this->erro_sql = " Campo j136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ruashistorico(
                                       j136_sequencial 
                                      ,j136_ruas 
                                      ,j136_ruastipo 
                                      ,j136_lei 
                                      ,j136_datalei 
                                      ,j136_nomeanterior 
                                      ,j136_dataalteracao 
                       )
                values (
                                $this->j136_sequencial 
                               ,$this->j136_ruas 
                               ,$this->j136_ruastipo 
                               ,'$this->j136_lei' 
                               ,".($this->j136_datalei == "null" || $this->j136_datalei == ""?"null":"'".$this->j136_datalei."'")." 
                               ,'$this->j136_nomeanterior' 
                               ,".($this->j136_dataalteracao == "null" || $this->j136_dataalteracao == ""?"null":"'".$this->j136_dataalteracao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ruashistorico ($this->j136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ruashistorico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ruashistorico ($this->j136_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j136_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j136_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20180,'$this->j136_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3623,20180,'','".AddSlashes(pg_result($resaco,0,'j136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20181,'','".AddSlashes(pg_result($resaco,0,'j136_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20185,'','".AddSlashes(pg_result($resaco,0,'j136_ruastipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20182,'','".AddSlashes(pg_result($resaco,0,'j136_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20183,'','".AddSlashes(pg_result($resaco,0,'j136_datalei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20184,'','".AddSlashes(pg_result($resaco,0,'j136_nomeanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3623,20186,'','".AddSlashes(pg_result($resaco,0,'j136_dataalteracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ruashistorico set ";
     $virgula = "";
     if(trim($this->j136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_sequencial"])){ 
       $sql  .= $virgula." j136_sequencial = $this->j136_sequencial ";
       $virgula = ",";
       if(trim($this->j136_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j136_ruas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_ruas"])){ 
       $sql  .= $virgula." j136_ruas = $this->j136_ruas ";
       $virgula = ",";
       if(trim($this->j136_ruas) == null ){ 
         $this->erro_sql = " Campo Código Logradouro nao Informado.";
         $this->erro_campo = "j136_ruas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j136_ruastipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_ruastipo"])){ 
       $sql  .= $virgula." j136_ruastipo = $this->j136_ruastipo ";
       $virgula = ",";
       if(trim($this->j136_ruastipo) == null ){ 
         $this->erro_sql = " Campo Código Tipo nao Informado.";
         $this->erro_campo = "j136_ruastipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j136_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_lei"])){ 
       $sql  .= $virgula." j136_lei = '$this->j136_lei' ";
       $virgula = ",";
     }
     if(trim($this->j136_datalei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_datalei_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j136_datalei_dia"] !="") ){ 
       $sql  .= $virgula." j136_datalei = '$this->j136_datalei' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j136_datalei_dia"])){ 
         $sql  .= $virgula." j136_datalei = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j136_nomeanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_nomeanterior"])){ 
       $sql  .= $virgula." j136_nomeanterior = '$this->j136_nomeanterior' ";
       $virgula = ",";
       if(trim($this->j136_nomeanterior) == null ){ 
         $this->erro_sql = " Campo Nome Anterior nao Informado.";
         $this->erro_campo = "j136_nomeanterior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j136_dataalteracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_dia"] !="") ){ 
       $sql  .= $virgula." j136_dataalteracao = '$this->j136_dataalteracao' ";
       $virgula = ",";
       if(trim($this->j136_dataalteracao) == null ){ 
         $this->erro_sql = " Campo Data Alteração nao Informado.";
         $this->erro_campo = "j136_dataalteracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao_dia"])){ 
         $sql  .= $virgula." j136_dataalteracao = null ";
         $virgula = ",";
         if(trim($this->j136_dataalteracao) == null ){ 
           $this->erro_sql = " Campo Data Alteração nao Informado.";
           $this->erro_campo = "j136_dataalteracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($j136_sequencial!=null){
       $sql .= " j136_sequencial = $this->j136_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j136_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20180,'$this->j136_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_sequencial"]) || $this->j136_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3623,20180,'".AddSlashes(pg_result($resaco,$conresaco,'j136_sequencial'))."','$this->j136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_ruas"]) || $this->j136_ruas != "")
             $resac = db_query("insert into db_acount values($acount,3623,20181,'".AddSlashes(pg_result($resaco,$conresaco,'j136_ruas'))."','$this->j136_ruas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_ruastipo"]) || $this->j136_ruastipo != "")
             $resac = db_query("insert into db_acount values($acount,3623,20185,'".AddSlashes(pg_result($resaco,$conresaco,'j136_ruastipo'))."','$this->j136_ruastipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_lei"]) || $this->j136_lei != "")
             $resac = db_query("insert into db_acount values($acount,3623,20182,'".AddSlashes(pg_result($resaco,$conresaco,'j136_lei'))."','$this->j136_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_datalei"]) || $this->j136_datalei != "")
             $resac = db_query("insert into db_acount values($acount,3623,20183,'".AddSlashes(pg_result($resaco,$conresaco,'j136_datalei'))."','$this->j136_datalei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_nomeanterior"]) || $this->j136_nomeanterior != "")
             $resac = db_query("insert into db_acount values($acount,3623,20184,'".AddSlashes(pg_result($resaco,$conresaco,'j136_nomeanterior'))."','$this->j136_nomeanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["j136_dataalteracao"]) || $this->j136_dataalteracao != "")
             $resac = db_query("insert into db_acount values($acount,3623,20186,'".AddSlashes(pg_result($resaco,$conresaco,'j136_dataalteracao'))."','$this->j136_dataalteracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ruashistorico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ruashistorico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j136_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($j136_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20180,'$j136_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3623,20180,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20181,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20185,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_ruastipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20182,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20183,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_datalei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20184,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_nomeanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3623,20186,'','".AddSlashes(pg_result($resaco,$iresaco,'j136_dataalteracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from ruashistorico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j136_sequencial = $j136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ruashistorico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j136_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ruashistorico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j136_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ruashistorico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruashistorico ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = ruashistorico.j136_ruas";
     $sql .= "      inner join ruastipo  on  ruastipo.j88_codigo = ruashistorico.j136_ruastipo";
     $sql2 = "";
     if($dbwhere==""){
       if($j136_sequencial!=null ){
         $sql2 .= " where ruashistorico.j136_sequencial = $j136_sequencial "; 
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
   function sql_query_file ( $j136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruashistorico ";
     $sql2 = "";
     if($dbwhere==""){
       if($j136_sequencial!=null ){
         $sql2 .= " where ruashistorico.j136_sequencial = $j136_sequencial "; 
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