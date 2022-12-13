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

//MODULO: fiscal
//CLASSE DA ENTIDADE termovist
class cl_termovist { 
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
   var $y91_termovist = 0; 
   var $y91_inscr = 0; 
   var $y91_datatermo_dia = null; 
   var $y91_datatermo_mes = null; 
   var $y91_datatermo_ano = null; 
   var $y91_datatermo = null; 
   var $y91_exerc = 0; 
   var $y91_codigo = 0; 
   var $y91_tipo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y91_termovist = int4 = Código do termo 
                 y91_inscr = int4 = Inscrição no ISSQN 
                 y91_datatermo = date = Data do termo 
                 y91_exerc = int4 = Exercicio 
                 y91_codigo = int4 = cód. Logradouro 
                 y91_tipo = varchar(3) = Origem do termo 
                 ";
   //funcao construtor da classe 
   function cl_termovist() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termovist"); 
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
       $this->y91_termovist = ($this->y91_termovist == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_termovist"]:$this->y91_termovist);
       $this->y91_inscr = ($this->y91_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_inscr"]:$this->y91_inscr);
       if($this->y91_datatermo == ""){
         $this->y91_datatermo_dia = ($this->y91_datatermo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_datatermo_dia"]:$this->y91_datatermo_dia);
         $this->y91_datatermo_mes = ($this->y91_datatermo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_datatermo_mes"]:$this->y91_datatermo_mes);
         $this->y91_datatermo_ano = ($this->y91_datatermo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_datatermo_ano"]:$this->y91_datatermo_ano);
         if($this->y91_datatermo_dia != ""){
            $this->y91_datatermo = $this->y91_datatermo_ano."-".$this->y91_datatermo_mes."-".$this->y91_datatermo_dia;
         }
       }
       $this->y91_exerc = ($this->y91_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_exerc"]:$this->y91_exerc);
       $this->y91_codigo = ($this->y91_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_codigo"]:$this->y91_codigo);
       $this->y91_tipo = ($this->y91_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_tipo"]:$this->y91_tipo);
     }else{
       $this->y91_termovist = ($this->y91_termovist == ""?@$GLOBALS["HTTP_POST_VARS"]["y91_termovist"]:$this->y91_termovist);
     }
   }
   // funcao para inclusao
   function incluir ($y91_termovist){ 
      $this->atualizacampos();
     if($this->y91_inscr == null ){ 
       $this->erro_sql = " Campo Inscrição no ISSQN nao Informado.";
       $this->erro_campo = "y91_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y91_datatermo == null ){ 
       $this->erro_sql = " Campo Data do termo nao Informado.";
       $this->erro_campo = "y91_datatermo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y91_exerc == null ){ 
       $this->erro_sql = " Campo Exercicio nao Informado.";
       $this->erro_campo = "y91_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y91_codigo == null ){ 
       $this->erro_sql = " Campo cód. Logradouro nao Informado.";
       $this->erro_campo = "y91_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y91_tipo == null ){ 
       $this->erro_sql = " Campo Origem do termo nao Informado.";
       $this->erro_campo = "y91_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y91_termovist == "" || $y91_termovist == null ){
       $result = db_query("select nextval('termovist_y91_termovist_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: termovist_y91_termovist_seq do campo: y91_termovist"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y91_termovist = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from termovist_y91_termovist_seq");
       if(($result != false) && (pg_result($result,0,0) < $y91_termovist)){
         $this->erro_sql = " Campo y91_termovist maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y91_termovist = $y91_termovist; 
       }
     }
     if(($this->y91_termovist == null) || ($this->y91_termovist == "") ){ 
       $this->erro_sql = " Campo y91_termovist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termovist(
                                       y91_termovist 
                                      ,y91_inscr 
                                      ,y91_datatermo 
                                      ,y91_exerc 
                                      ,y91_codigo 
                                      ,y91_tipo 
                       )
                values (
                                $this->y91_termovist 
                               ,$this->y91_inscr 
                               ,".($this->y91_datatermo == "null" || $this->y91_datatermo == ""?"null":"'".$this->y91_datatermo."'")." 
                               ,$this->y91_exerc 
                               ,$this->y91_codigo 
                               ,'$this->y91_tipo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Termo de vistorias ($this->y91_termovist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Termo de vistorias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Termo de vistorias ($this->y91_termovist) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y91_termovist;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y91_termovist));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8001,'$this->y91_termovist','I')");
       $resac = db_query("insert into db_acount values($acount,1348,8001,'','".AddSlashes(pg_result($resaco,0,'y91_termovist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1348,8002,'','".AddSlashes(pg_result($resaco,0,'y91_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1348,8003,'','".AddSlashes(pg_result($resaco,0,'y91_datatermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1348,8004,'','".AddSlashes(pg_result($resaco,0,'y91_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1348,8012,'','".AddSlashes(pg_result($resaco,0,'y91_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1348,8013,'','".AddSlashes(pg_result($resaco,0,'y91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y91_termovist=null) { 
      $this->atualizacampos();
     $sql = " update termovist set ";
     $virgula = "";
     if(trim($this->y91_termovist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_termovist"])){ 
       $sql  .= $virgula." y91_termovist = $this->y91_termovist ";
       $virgula = ",";
       if(trim($this->y91_termovist) == null ){ 
         $this->erro_sql = " Campo Código do termo nao Informado.";
         $this->erro_campo = "y91_termovist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y91_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_inscr"])){ 
       $sql  .= $virgula." y91_inscr = $this->y91_inscr ";
       $virgula = ",";
       if(trim($this->y91_inscr) == null ){ 
         $this->erro_sql = " Campo Inscrição no ISSQN nao Informado.";
         $this->erro_campo = "y91_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y91_datatermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_datatermo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y91_datatermo_dia"] !="") ){ 
       $sql  .= $virgula." y91_datatermo = '$this->y91_datatermo' ";
       $virgula = ",";
       if(trim($this->y91_datatermo) == null ){ 
         $this->erro_sql = " Campo Data do termo nao Informado.";
         $this->erro_campo = "y91_datatermo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y91_datatermo_dia"])){ 
         $sql  .= $virgula." y91_datatermo = null ";
         $virgula = ",";
         if(trim($this->y91_datatermo) == null ){ 
           $this->erro_sql = " Campo Data do termo nao Informado.";
           $this->erro_campo = "y91_datatermo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y91_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_exerc"])){ 
       $sql  .= $virgula." y91_exerc = $this->y91_exerc ";
       $virgula = ",";
       if(trim($this->y91_exerc) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "y91_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y91_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_codigo"])){ 
       $sql  .= $virgula." y91_codigo = $this->y91_codigo ";
       $virgula = ",";
       if(trim($this->y91_codigo) == null ){ 
         $this->erro_sql = " Campo cód. Logradouro nao Informado.";
         $this->erro_campo = "y91_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y91_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y91_tipo"])){ 
       $sql  .= $virgula." y91_tipo = '$this->y91_tipo' ";
       $virgula = ",";
       if(trim($this->y91_tipo) == null ){ 
         $this->erro_sql = " Campo Origem do termo nao Informado.";
         $this->erro_campo = "y91_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y91_termovist!=null){
       $sql .= " y91_termovist = $this->y91_termovist";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y91_termovist));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8001,'$this->y91_termovist','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_termovist"]))
           $resac = db_query("insert into db_acount values($acount,1348,8001,'".AddSlashes(pg_result($resaco,$conresaco,'y91_termovist'))."','$this->y91_termovist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_inscr"]))
           $resac = db_query("insert into db_acount values($acount,1348,8002,'".AddSlashes(pg_result($resaco,$conresaco,'y91_inscr'))."','$this->y91_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_datatermo"]))
           $resac = db_query("insert into db_acount values($acount,1348,8003,'".AddSlashes(pg_result($resaco,$conresaco,'y91_datatermo'))."','$this->y91_datatermo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_exerc"]))
           $resac = db_query("insert into db_acount values($acount,1348,8004,'".AddSlashes(pg_result($resaco,$conresaco,'y91_exerc'))."','$this->y91_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1348,8012,'".AddSlashes(pg_result($resaco,$conresaco,'y91_codigo'))."','$this->y91_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y91_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1348,8013,'".AddSlashes(pg_result($resaco,$conresaco,'y91_tipo'))."','$this->y91_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Termo de vistorias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y91_termovist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Termo de vistorias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y91_termovist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y91_termovist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y91_termovist=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y91_termovist));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8001,'$y91_termovist','E')");
         $resac = db_query("insert into db_acount values($acount,1348,8001,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_termovist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1348,8002,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1348,8003,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_datatermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1348,8004,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1348,8012,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1348,8013,'','".AddSlashes(pg_result($resaco,$iresaco,'y91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termovist
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y91_termovist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y91_termovist = $y91_termovist ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Termo de vistorias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y91_termovist;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Termo de vistorias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y91_termovist;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y91_termovist;
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
        $this->erro_sql   = "Record Vazio na Tabela:termovist";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y91_termovist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termovist ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = termovist.y91_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($y91_termovist!=null ){
         $sql2 .= " where termovist.y91_termovist = $y91_termovist "; 
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
   function sql_query_file ( $y91_termovist=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termovist ";
     $sql2 = "";
     if($dbwhere==""){
       if($y91_termovist!=null ){
         $sql2 .= " where termovist.y91_termovist = $y91_termovist "; 
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