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

//MODULO: Fiscal
//CLASSE DA ENTIDADE autotipobaixaproc
class cl_autotipobaixaproc { 
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
   var $y87_baixaproc = 0; 
   var $y87_dtbaixa_dia = null; 
   var $y87_dtbaixa_mes = null; 
   var $y87_dtbaixa_ano = null; 
   var $y87_dtbaixa = null; 
   var $y87_usuario = 0; 
   var $y87_data_dia = null; 
   var $y87_data_mes = null; 
   var $y87_data_ano = null; 
   var $y87_data = null; 
   var $y87_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y87_baixaproc = int4 = Codigo da Baixa da Procedência do auto 
                 y87_dtbaixa = date = Data da Baixa 
                 y87_usuario = int4 = Usuário 
                 y87_data = date = Data 
                 y87_hora = varchar(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_autotipobaixaproc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("autotipobaixaproc"); 
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
       $this->y87_baixaproc = ($this->y87_baixaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_baixaproc"]:$this->y87_baixaproc);
       if($this->y87_dtbaixa == ""){
         $this->y87_dtbaixa_dia = ($this->y87_dtbaixa_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_dia"]:$this->y87_dtbaixa_dia);
         $this->y87_dtbaixa_mes = ($this->y87_dtbaixa_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_mes"]:$this->y87_dtbaixa_mes);
         $this->y87_dtbaixa_ano = ($this->y87_dtbaixa_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_ano"]:$this->y87_dtbaixa_ano);
         if($this->y87_dtbaixa_dia != ""){
            $this->y87_dtbaixa = $this->y87_dtbaixa_ano."-".$this->y87_dtbaixa_mes."-".$this->y87_dtbaixa_dia;
         }
       }
       $this->y87_usuario = ($this->y87_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_usuario"]:$this->y87_usuario);
       if($this->y87_data == ""){
         $this->y87_data_dia = ($this->y87_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_data_dia"]:$this->y87_data_dia);
         $this->y87_data_mes = ($this->y87_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_data_mes"]:$this->y87_data_mes);
         $this->y87_data_ano = ($this->y87_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_data_ano"]:$this->y87_data_ano);
         if($this->y87_data_dia != ""){
            $this->y87_data = $this->y87_data_ano."-".$this->y87_data_mes."-".$this->y87_data_dia;
         }
       }
       $this->y87_hora = ($this->y87_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_hora"]:$this->y87_hora);
     }else{
       $this->y87_baixaproc = ($this->y87_baixaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["y87_baixaproc"]:$this->y87_baixaproc);
     }
   }
   // funcao para inclusao
   function incluir ($y87_baixaproc){ 
      $this->atualizacampos();
     if($this->y87_dtbaixa == null ){ 
       $this->erro_sql = " Campo Data da Baixa nao Informado.";
       $this->erro_campo = "y87_dtbaixa_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y87_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "y87_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y87_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "y87_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y87_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "y87_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y87_baixaproc == "" || $y87_baixaproc == null ){
       $result = db_query("select nextval('autotipobaixaproc_y87_baixaproc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autotipobaixaproc_y87_baixaproc_seq do campo: y87_baixaproc"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y87_baixaproc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from autotipobaixaproc_y87_baixaproc_seq");
       if(($result != false) && (pg_result($result,0,0) < $y87_baixaproc)){
         $this->erro_sql = " Campo y87_baixaproc maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y87_baixaproc = $y87_baixaproc; 
       }
     }
     if(($this->y87_baixaproc == null) || ($this->y87_baixaproc == "") ){ 
       $this->erro_sql = " Campo y87_baixaproc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autotipobaixaproc(
                                       y87_baixaproc 
                                      ,y87_dtbaixa 
                                      ,y87_usuario 
                                      ,y87_data 
                                      ,y87_hora 
                       )
                values (
                                $this->y87_baixaproc 
                               ,".($this->y87_dtbaixa == "null" || $this->y87_dtbaixa == ""?"null":"'".$this->y87_dtbaixa."'")." 
                               ,$this->y87_usuario 
                               ,".($this->y87_data == "null" || $this->y87_data == ""?"null":"'".$this->y87_data."'")." 
                               ,'$this->y87_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Baixa procedências do auto ($this->y87_baixaproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Baixa procedências do auto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Baixa procedências do auto ($this->y87_baixaproc) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y87_baixaproc;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y87_baixaproc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6798,'$this->y87_baixaproc','I')");
       $resac = db_query("insert into db_acount values($acount,1112,6798,'','".AddSlashes(pg_result($resaco,0,'y87_baixaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1112,6800,'','".AddSlashes(pg_result($resaco,0,'y87_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1112,6801,'','".AddSlashes(pg_result($resaco,0,'y87_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1112,6802,'','".AddSlashes(pg_result($resaco,0,'y87_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1112,6803,'','".AddSlashes(pg_result($resaco,0,'y87_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y87_baixaproc=null) { 
      $this->atualizacampos();
     $sql = " update autotipobaixaproc set ";
     $virgula = "";
     if(trim($this->y87_baixaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y87_baixaproc"])){ 
       $sql  .= $virgula." y87_baixaproc = $this->y87_baixaproc ";
       $virgula = ",";
       if(trim($this->y87_baixaproc) == null ){ 
         $this->erro_sql = " Campo Codigo da Baixa da Procedência do auto nao Informado.";
         $this->erro_campo = "y87_baixaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y87_dtbaixa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_dia"] !="") ){ 
       $sql  .= $virgula." y87_dtbaixa = '$this->y87_dtbaixa' ";
       $virgula = ",";
       if(trim($this->y87_dtbaixa) == null ){ 
         $this->erro_sql = " Campo Data da Baixa nao Informado.";
         $this->erro_campo = "y87_dtbaixa_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa_dia"])){ 
         $sql  .= $virgula." y87_dtbaixa = null ";
         $virgula = ",";
         if(trim($this->y87_dtbaixa) == null ){ 
           $this->erro_sql = " Campo Data da Baixa nao Informado.";
           $this->erro_campo = "y87_dtbaixa_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y87_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y87_usuario"])){ 
       $sql  .= $virgula." y87_usuario = $this->y87_usuario ";
       $virgula = ",";
       if(trim($this->y87_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "y87_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y87_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y87_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y87_data_dia"] !="") ){ 
       $sql  .= $virgula." y87_data = '$this->y87_data' ";
       $virgula = ",";
       if(trim($this->y87_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "y87_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y87_data_dia"])){ 
         $sql  .= $virgula." y87_data = null ";
         $virgula = ",";
         if(trim($this->y87_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "y87_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y87_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y87_hora"])){ 
       $sql  .= $virgula." y87_hora = '$this->y87_hora' ";
       $virgula = ",";
       if(trim($this->y87_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "y87_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y87_baixaproc!=null){
       $sql .= " y87_baixaproc = $this->y87_baixaproc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y87_baixaproc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6798,'$this->y87_baixaproc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y87_baixaproc"]) || $this->y87_baixaproc != "")
           $resac = db_query("insert into db_acount values($acount,1112,6798,'".AddSlashes(pg_result($resaco,$conresaco,'y87_baixaproc'))."','$this->y87_baixaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y87_dtbaixa"]) || $this->y87_dtbaixa != "")
           $resac = db_query("insert into db_acount values($acount,1112,6800,'".AddSlashes(pg_result($resaco,$conresaco,'y87_dtbaixa'))."','$this->y87_dtbaixa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y87_usuario"]) || $this->y87_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1112,6801,'".AddSlashes(pg_result($resaco,$conresaco,'y87_usuario'))."','$this->y87_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y87_data"]) || $this->y87_data != "")
           $resac = db_query("insert into db_acount values($acount,1112,6802,'".AddSlashes(pg_result($resaco,$conresaco,'y87_data'))."','$this->y87_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y87_hora"]) || $this->y87_hora != "")
           $resac = db_query("insert into db_acount values($acount,1112,6803,'".AddSlashes(pg_result($resaco,$conresaco,'y87_hora'))."','$this->y87_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa procedências do auto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y87_baixaproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa procedências do auto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y87_baixaproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y87_baixaproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y87_baixaproc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y87_baixaproc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6798,'$y87_baixaproc','E')");
         $resac = db_query("insert into db_acount values($acount,1112,6798,'','".AddSlashes(pg_result($resaco,$iresaco,'y87_baixaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1112,6800,'','".AddSlashes(pg_result($resaco,$iresaco,'y87_dtbaixa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1112,6801,'','".AddSlashes(pg_result($resaco,$iresaco,'y87_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1112,6802,'','".AddSlashes(pg_result($resaco,$iresaco,'y87_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1112,6803,'','".AddSlashes(pg_result($resaco,$iresaco,'y87_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from autotipobaixaproc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y87_baixaproc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y87_baixaproc = $y87_baixaproc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Baixa procedências do auto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y87_baixaproc;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Baixa procedências do auto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y87_baixaproc;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y87_baixaproc;
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
        $this->erro_sql   = "Record Vazio na Tabela:autotipobaixaproc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $y87_baixaproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autotipobaixaproc ";
     $sql .= "      inner join db_usuarios            on db_usuarios.id_usuario                = autotipobaixaproc.y87_usuario        ";
     $sql .= "      left  join autotipobaixaprocproc  on autotipobaixaprocproc.y114_baixaproc  = autotipobaixaproc.y87_baixaproc      ";
     $sql .= "      left  join protprocesso           on protprocesso.p58_codproc              = autotipobaixaprocproc.y114_processo  ";
     $sql .= "      inner join cgm                    on cgm.z01_numcgm                        = protprocesso.p58_numcgm              ";
     $sql .= "      inner join db_usuarios            on db_usuarios.id_usuario                = protprocesso.p58_id_usuario          ";
     $sql .= "      inner join db_depart              on db_depart.coddepto                    = protprocesso.p58_coddepto            ";
     $sql .= "      inner join tipoproc               on tipoproc.p51_codigo                   = protprocesso.p58_codigo              ";
     $sql2 = "";
     if($dbwhere==""){
       if($y87_baixaproc!=null ){
         $sql2 .= " where autotipobaixaproc.y87_baixaproc = $y87_baixaproc "; 
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
   function sql_query_file ( $y87_baixaproc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from autotipobaixaproc ";
     $sql2 = "";
     if($dbwhere==""){
       if($y87_baixaproc!=null ){
         $sql2 .= " where autotipobaixaproc.y87_baixaproc = $y87_baixaproc "; 
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