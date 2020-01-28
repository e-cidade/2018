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

//MODULO: licitação
//CLASSE DA ENTIDADE liclicitaforne
class cl_liclicitaforne { 
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
   var $l22_codigo = 0; 
   var $l22_codliclicita = 0; 
   var $l22_numcgm = 0; 
   var $l22_dtretira_dia = null; 
   var $l22_dtretira_mes = null; 
   var $l22_dtretira_ano = null; 
   var $l22_dtretira = null; 
   var $l22_nomeretira = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l22_codigo = int8 = Cod. Sequencial 
                 l22_codliclicita = int8 = Cod. Licitação 
                 l22_numcgm = int4 = Numcgm 
                 l22_dtretira = date = Data da Retirada do Edital 
                 l22_nomeretira = varchar(100) = Nome da Pessoa que Retirou o Edital 
                 ";
   //funcao construtor da classe 
   function cl_liclicitaforne() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitaforne"); 
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
       $this->l22_codigo = ($this->l22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_codigo"]:$this->l22_codigo);
       $this->l22_codliclicita = ($this->l22_codliclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_codliclicita"]:$this->l22_codliclicita);
       $this->l22_numcgm = ($this->l22_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_numcgm"]:$this->l22_numcgm);
       if($this->l22_dtretira == ""){
         $this->l22_dtretira_dia = ($this->l22_dtretira_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_dtretira_dia"]:$this->l22_dtretira_dia);
         $this->l22_dtretira_mes = ($this->l22_dtretira_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_dtretira_mes"]:$this->l22_dtretira_mes);
         $this->l22_dtretira_ano = ($this->l22_dtretira_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_dtretira_ano"]:$this->l22_dtretira_ano);
         if($this->l22_dtretira_dia != ""){
            $this->l22_dtretira = $this->l22_dtretira_ano."-".$this->l22_dtretira_mes."-".$this->l22_dtretira_dia;
         }
       }
       $this->l22_nomeretira = ($this->l22_nomeretira == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_nomeretira"]:$this->l22_nomeretira);
     }else{
       $this->l22_codigo = ($this->l22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l22_codigo"]:$this->l22_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l22_codigo){ 
      $this->atualizacampos();
     if($this->l22_codliclicita == null ){ 
       $this->erro_sql = " Campo Cod. Licitação nao Informado.";
       $this->erro_campo = "l22_codliclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l22_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "l22_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l22_dtretira == null ){ 
       $this->erro_sql = " Campo Data da Retirada do Edital nao Informado.";
       $this->erro_campo = "l22_dtretira_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l22_nomeretira == null ){ 
       $this->erro_sql = " Campo Nome da Pessoa que Retirou o Edital nao Informado.";
       $this->erro_campo = "l22_nomeretira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l22_codigo == "" || $l22_codigo == null ){
       $result = db_query("select nextval('liclicitaforne_l22_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaforne_l22_codigo_seq do campo: l22_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l22_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitaforne_l22_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l22_codigo)){
         $this->erro_sql = " Campo l22_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l22_codigo = $l22_codigo; 
       }
     }
     if(($this->l22_codigo == null) || ($this->l22_codigo == "") ){ 
       $this->erro_sql = " Campo l22_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitaforne(
                                       l22_codigo 
                                      ,l22_codliclicita 
                                      ,l22_numcgm 
                                      ,l22_dtretira 
                                      ,l22_nomeretira 
                       )
                values (
                                $this->l22_codigo 
                               ,$this->l22_codliclicita 
                               ,$this->l22_numcgm 
                               ,".($this->l22_dtretira == "null" || $this->l22_dtretira == ""?"null":"'".$this->l22_dtretira."'")." 
                               ,'$this->l22_nomeretira' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "liclicitaforne ($this->l22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "liclicitaforne já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "liclicitaforne ($this->l22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l22_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l22_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7603,'$this->l22_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1262,7603,'','".AddSlashes(pg_result($resaco,0,'l22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1262,7604,'','".AddSlashes(pg_result($resaco,0,'l22_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1262,7605,'','".AddSlashes(pg_result($resaco,0,'l22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1262,7849,'','".AddSlashes(pg_result($resaco,0,'l22_dtretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1262,7850,'','".AddSlashes(pg_result($resaco,0,'l22_nomeretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l22_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaforne set ";
     $virgula = "";
     if(trim($this->l22_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l22_codigo"])){ 
       $sql  .= $virgula." l22_codigo = $this->l22_codigo ";
       $virgula = ",";
       if(trim($this->l22_codigo) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "l22_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l22_codliclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l22_codliclicita"])){ 
       $sql  .= $virgula." l22_codliclicita = $this->l22_codliclicita ";
       $virgula = ",";
       if(trim($this->l22_codliclicita) == null ){ 
         $this->erro_sql = " Campo Cod. Licitação nao Informado.";
         $this->erro_campo = "l22_codliclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l22_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l22_numcgm"])){ 
       $sql  .= $virgula." l22_numcgm = $this->l22_numcgm ";
       $virgula = ",";
       if(trim($this->l22_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "l22_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l22_dtretira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l22_dtretira_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l22_dtretira_dia"] !="") ){ 
       $sql  .= $virgula." l22_dtretira = '$this->l22_dtretira' ";
       $virgula = ",";
       if(trim($this->l22_dtretira) == null ){ 
         $this->erro_sql = " Campo Data da Retirada do Edital nao Informado.";
         $this->erro_campo = "l22_dtretira_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l22_dtretira_dia"])){ 
         $sql  .= $virgula." l22_dtretira = null ";
         $virgula = ",";
         if(trim($this->l22_dtretira) == null ){ 
           $this->erro_sql = " Campo Data da Retirada do Edital nao Informado.";
           $this->erro_campo = "l22_dtretira_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l22_nomeretira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l22_nomeretira"])){ 
       $sql  .= $virgula." l22_nomeretira = '$this->l22_nomeretira' ";
       $virgula = ",";
       if(trim($this->l22_nomeretira) == null ){ 
         $this->erro_sql = " Campo Nome da Pessoa que Retirou o Edital nao Informado.";
         $this->erro_campo = "l22_nomeretira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l22_codigo!=null){
       $sql .= " l22_codigo = $this->l22_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l22_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7603,'$this->l22_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l22_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1262,7603,'".AddSlashes(pg_result($resaco,$conresaco,'l22_codigo'))."','$this->l22_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l22_codliclicita"]))
           $resac = db_query("insert into db_acount values($acount,1262,7604,'".AddSlashes(pg_result($resaco,$conresaco,'l22_codliclicita'))."','$this->l22_codliclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l22_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1262,7605,'".AddSlashes(pg_result($resaco,$conresaco,'l22_numcgm'))."','$this->l22_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l22_dtretira"]))
           $resac = db_query("insert into db_acount values($acount,1262,7849,'".AddSlashes(pg_result($resaco,$conresaco,'l22_dtretira'))."','$this->l22_dtretira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l22_nomeretira"]))
           $resac = db_query("insert into db_acount values($acount,1262,7850,'".AddSlashes(pg_result($resaco,$conresaco,'l22_nomeretira'))."','$this->l22_nomeretira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaforne nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaforne nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l22_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l22_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7603,'$l22_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1262,7603,'','".AddSlashes(pg_result($resaco,$iresaco,'l22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1262,7604,'','".AddSlashes(pg_result($resaco,$iresaco,'l22_codliclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1262,7605,'','".AddSlashes(pg_result($resaco,$iresaco,'l22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1262,7849,'','".AddSlashes(pg_result($resaco,$iresaco,'l22_dtretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1262,7850,'','".AddSlashes(pg_result($resaco,$iresaco,'l22_nomeretira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitaforne
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l22_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l22_codigo = $l22_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "liclicitaforne nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "liclicitaforne nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l22_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaforne";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaforne ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = liclicitaforne.l22_numcgm";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitaforne.l22_codliclicita";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
     $sql2 = "";
     if($dbwhere==""){
       if($l22_codigo!=null ){
         $sql2 .= " where liclicitaforne.l22_codigo = $l22_codigo "; 
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
   function sql_query_file ( $l22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitaforne ";
     $sql2 = "";
     if($dbwhere==""){
       if($l22_codigo!=null ){
         $sql2 .= " where liclicitaforne.l22_codigo = $l22_codigo "; 
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