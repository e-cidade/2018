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

//MODULO: cadastro
//CLASSE DA ENTIDADE averbaescritura
class cl_averbaescritura { 
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
   var $j94_codigo = 0; 
   var $j94_averbacao = 0; 
   var $j94_livro = null; 
   var $j94_folha = null; 
   var $j94_numero = null; 
   var $j94_tabelionato = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j94_codigo = int4 = Código 
                 j94_averbacao = int4 = Código Averbação 
                 j94_livro = varchar(10) = Livro 
                 j94_folha = varchar(10) = Folha 
                 j94_numero = varchar(20) = Número 
                 j94_tabelionato = varchar(40) = Tabelionato 
                 ";
   //funcao construtor da classe 
   function cl_averbaescritura() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averbaescritura"); 
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
       $this->j94_codigo = ($this->j94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_codigo"]:$this->j94_codigo);
       $this->j94_averbacao = ($this->j94_averbacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_averbacao"]:$this->j94_averbacao);
       $this->j94_livro = ($this->j94_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_livro"]:$this->j94_livro);
       $this->j94_folha = ($this->j94_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_folha"]:$this->j94_folha);
       $this->j94_numero = ($this->j94_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_numero"]:$this->j94_numero);
       $this->j94_tabelionato = ($this->j94_tabelionato == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_tabelionato"]:$this->j94_tabelionato);
     }else{
       $this->j94_codigo = ($this->j94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j94_codigo"]:$this->j94_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j94_codigo){ 
      $this->atualizacampos();
     if($this->j94_averbacao == null ){ 
       $this->erro_sql = " Campo Código Averbação nao Informado.";
       $this->erro_campo = "j94_averbacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j94_livro == null ){ 
       $this->erro_sql = " Campo Livro nao Informado.";
       $this->erro_campo = "j94_livro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j94_folha == null ){ 
       $this->erro_sql = " Campo Folha nao Informado.";
       $this->erro_campo = "j94_folha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j94_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "j94_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j94_tabelionato == null ){ 
       $this->erro_sql = " Campo Tabelionato nao Informado.";
       $this->erro_campo = "j94_tabelionato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j94_codigo == "" || $j94_codigo == null ){
       $result = db_query("select nextval('averbaescritura_j94_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averbaescritura_j94_codigo_seq do campo: j94_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j94_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from averbaescritura_j94_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j94_codigo)){
         $this->erro_sql = " Campo j94_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j94_codigo = $j94_codigo; 
       }
     }
     if(($this->j94_codigo == null) || ($this->j94_codigo == "") ){ 
       $this->erro_sql = " Campo j94_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averbaescritura(
                                       j94_codigo 
                                      ,j94_averbacao 
                                      ,j94_livro 
                                      ,j94_folha 
                                      ,j94_numero 
                                      ,j94_tabelionato 
                       )
                values (
                                $this->j94_codigo 
                               ,$this->j94_averbacao 
                               ,'$this->j94_livro' 
                               ,'$this->j94_folha' 
                               ,'$this->j94_numero' 
                               ,'$this->j94_tabelionato' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "averbaescritura ($this->j94_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "averbaescritura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "averbaescritura ($this->j94_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j94_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j94_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9680,'$this->j94_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1666,9680,'','".AddSlashes(pg_result($resaco,0,'j94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1666,9681,'','".AddSlashes(pg_result($resaco,0,'j94_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1666,9682,'','".AddSlashes(pg_result($resaco,0,'j94_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1666,9683,'','".AddSlashes(pg_result($resaco,0,'j94_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1666,9684,'','".AddSlashes(pg_result($resaco,0,'j94_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1666,9685,'','".AddSlashes(pg_result($resaco,0,'j94_tabelionato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j94_codigo=null) { 
      $this->atualizacampos();
     $sql = " update averbaescritura set ";
     $virgula = "";
     if(trim($this->j94_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_codigo"])){ 
       $sql  .= $virgula." j94_codigo = $this->j94_codigo ";
       $virgula = ",";
       if(trim($this->j94_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j94_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j94_averbacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_averbacao"])){ 
       $sql  .= $virgula." j94_averbacao = $this->j94_averbacao ";
       $virgula = ",";
       if(trim($this->j94_averbacao) == null ){ 
         $this->erro_sql = " Campo Código Averbação nao Informado.";
         $this->erro_campo = "j94_averbacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j94_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_livro"])){ 
       $sql  .= $virgula." j94_livro = '$this->j94_livro' ";
       $virgula = ",";
       if(trim($this->j94_livro) == null ){ 
         $this->erro_sql = " Campo Livro nao Informado.";
         $this->erro_campo = "j94_livro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j94_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_folha"])){ 
       $sql  .= $virgula." j94_folha = '$this->j94_folha' ";
       $virgula = ",";
       if(trim($this->j94_folha) == null ){ 
         $this->erro_sql = " Campo Folha nao Informado.";
         $this->erro_campo = "j94_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j94_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_numero"])){ 
       $sql  .= $virgula." j94_numero = '$this->j94_numero' ";
       $virgula = ",";
       if(trim($this->j94_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "j94_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j94_tabelionato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j94_tabelionato"])){ 
       $sql  .= $virgula." j94_tabelionato = '$this->j94_tabelionato' ";
       $virgula = ",";
       if(trim($this->j94_tabelionato) == null ){ 
         $this->erro_sql = " Campo Tabelionato nao Informado.";
         $this->erro_campo = "j94_tabelionato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j94_codigo!=null){
       $sql .= " j94_codigo = $this->j94_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j94_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9680,'$this->j94_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1666,9680,'".AddSlashes(pg_result($resaco,$conresaco,'j94_codigo'))."','$this->j94_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_averbacao"]))
           $resac = db_query("insert into db_acount values($acount,1666,9681,'".AddSlashes(pg_result($resaco,$conresaco,'j94_averbacao'))."','$this->j94_averbacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_livro"]))
           $resac = db_query("insert into db_acount values($acount,1666,9682,'".AddSlashes(pg_result($resaco,$conresaco,'j94_livro'))."','$this->j94_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_folha"]))
           $resac = db_query("insert into db_acount values($acount,1666,9683,'".AddSlashes(pg_result($resaco,$conresaco,'j94_folha'))."','$this->j94_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_numero"]))
           $resac = db_query("insert into db_acount values($acount,1666,9684,'".AddSlashes(pg_result($resaco,$conresaco,'j94_numero'))."','$this->j94_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j94_tabelionato"]))
           $resac = db_query("insert into db_acount values($acount,1666,9685,'".AddSlashes(pg_result($resaco,$conresaco,'j94_tabelionato'))."','$this->j94_tabelionato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "averbaescritura nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "averbaescritura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j94_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j94_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9680,'$j94_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1666,9680,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1666,9681,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1666,9682,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1666,9683,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1666,9684,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1666,9685,'','".AddSlashes(pg_result($resaco,$iresaco,'j94_tabelionato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averbaescritura
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j94_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j94_codigo = $j94_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "averbaescritura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "averbaescritura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j94_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:averbaescritura";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j94_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbaescritura ";
     $sql .= "      inner join averbacao  on  averbacao.j75_codigo = averbaescritura.j94_averbacao";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = averbacao.j75_matric";
     $sql .= "      inner join averbatipo  on  averbatipo.j93_codigo = averbacao.j75_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($j94_codigo!=null ){
         $sql2 .= " where averbaescritura.j94_codigo = $j94_codigo "; 
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
   function sql_query_file ( $j94_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbaescritura ";
     $sql2 = "";
     if($dbwhere==""){
       if($j94_codigo!=null ){
         $sql2 .= " where averbaescritura.j94_codigo = $j94_codigo "; 
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