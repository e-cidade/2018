<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_resultadonum
class cl_lab_resultadonum { 
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
   var $la41_i_codigo = 0; 
   var $la41_i_result = 0; 
   var $la41_f_valor = 0; 
   var $la41_valorpercentual = 0; 
   var $la41_faixaescolhida = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la41_i_codigo = int4 = Código 
                 la41_i_result = int4 = Resultado 
                 la41_f_valor = float4 = Valor 
                 la41_valorpercentual = float4 = Valor percentual 
                 la41_faixaescolhida = int4 = Faixa escolhida para o exame 
                 ";
   //funcao construtor da classe 
   function cl_lab_resultadonum() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_resultadonum"); 
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
       $this->la41_i_codigo = ($this->la41_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_i_codigo"]:$this->la41_i_codigo);
       $this->la41_i_result = ($this->la41_i_result == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_i_result"]:$this->la41_i_result);
       $this->la41_f_valor = ($this->la41_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_f_valor"]:$this->la41_f_valor);
       $this->la41_valorpercentual = ($this->la41_valorpercentual == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_valorpercentual"]:$this->la41_valorpercentual);
       $this->la41_faixaescolhida = ($this->la41_faixaescolhida == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_faixaescolhida"]:$this->la41_faixaescolhida);
     }else{
       $this->la41_i_codigo = ($this->la41_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la41_i_codigo"]:$this->la41_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la41_i_codigo){ 
      $this->atualizacampos();
     if($this->la41_i_result == null ){ 
       $this->erro_sql = " Campo Resultado não informado.";
       $this->erro_campo = "la41_i_result";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->la41_f_valor == '' ){
       $this->la41_f_valor = "null";
     }
     if($this->la41_valorpercentual == null ){ 
       $this->la41_valorpercentual = "null";
     }
     if($this->la41_faixaescolhida == null ){ 
       $this->la41_faixaescolhida = "null";
     }
     if($la41_i_codigo == "" || $la41_i_codigo == null ){
       $result = db_query("select nextval('lab_resultadonum_la41_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_resultadonum_la41_i_codigo_seq do campo: la41_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la41_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_resultadonum_la41_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la41_i_codigo)){
         $this->erro_sql = " Campo la41_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la41_i_codigo = $la41_i_codigo; 
       }
     }
     if(($this->la41_i_codigo == null) || ($this->la41_i_codigo == "") ){ 
       $this->erro_sql = " Campo la41_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_resultadonum(
                                       la41_i_codigo 
                                      ,la41_i_result 
                                      ,la41_f_valor 
                                      ,la41_valorpercentual 
                                      ,la41_faixaescolhida 
                       )
                values (
                                $this->la41_i_codigo 
                               ,$this->la41_i_result 
                               ,$this->la41_f_valor 
                               ,$this->la41_valorpercentual 
                               ,$this->la41_faixaescolhida 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado em Número ($this->la41_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado em Número já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado em Número ($this->la41_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la41_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la41_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16516,'$this->la41_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2898,16516,'','".AddSlashes(pg_result($resaco,0,'la41_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2898,16517,'','".AddSlashes(pg_result($resaco,0,'la41_i_result'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2898,16518,'','".AddSlashes(pg_result($resaco,0,'la41_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2898,20496,'','".AddSlashes(pg_result($resaco,0,'la41_valorpercentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2898,20497,'','".AddSlashes(pg_result($resaco,0,'la41_faixaescolhida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la41_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_resultadonum set ";
     $virgula = "";
     if(trim($this->la41_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la41_i_codigo"])){ 
       $sql  .= $virgula." la41_i_codigo = $this->la41_i_codigo ";
       $virgula = ",";
       if(trim($this->la41_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la41_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la41_i_result)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la41_i_result"])){ 
       $sql  .= $virgula." la41_i_result = $this->la41_i_result ";
       $virgula = ",";
       if(trim($this->la41_i_result) == null ){ 
         $this->erro_sql = " Campo Resultado não informado.";
         $this->erro_campo = "la41_i_result";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la41_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la41_f_valor"])){ 
       $sql  .= $virgula." la41_f_valor = $this->la41_f_valor ";
       $virgula = ",";
       if(trim($this->la41_f_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "la41_f_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la41_valorpercentual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la41_valorpercentual"])){ 
        if(trim($this->la41_valorpercentual)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la41_valorpercentual"])){ 
           $this->la41_valorpercentual = "0" ; 
        } 
       $sql  .= $virgula." la41_valorpercentual = $this->la41_valorpercentual ";
       $virgula = ",";
     }
     if(trim($this->la41_faixaescolhida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la41_faixaescolhida"])){ 
        if(trim($this->la41_faixaescolhida)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la41_faixaescolhida"])){ 
           $this->la41_faixaescolhida = "0" ; 
        } 
       $sql  .= $virgula." la41_faixaescolhida = $this->la41_faixaescolhida ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la41_i_codigo!=null){
       $sql .= " la41_i_codigo = $this->la41_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la41_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16516,'$this->la41_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la41_i_codigo"]) || $this->la41_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2898,16516,'".AddSlashes(pg_result($resaco,$conresaco,'la41_i_codigo'))."','$this->la41_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la41_i_result"]) || $this->la41_i_result != "")
             $resac = db_query("insert into db_acount values($acount,2898,16517,'".AddSlashes(pg_result($resaco,$conresaco,'la41_i_result'))."','$this->la41_i_result',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la41_f_valor"]) || $this->la41_f_valor != "")
             $resac = db_query("insert into db_acount values($acount,2898,16518,'".AddSlashes(pg_result($resaco,$conresaco,'la41_f_valor'))."','$this->la41_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la41_valorpercentual"]) || $this->la41_valorpercentual != "")
             $resac = db_query("insert into db_acount values($acount,2898,20496,'".AddSlashes(pg_result($resaco,$conresaco,'la41_valorpercentual'))."','$this->la41_valorpercentual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["la41_faixaescolhida"]) || $this->la41_faixaescolhida != "")
             $resac = db_query("insert into db_acount values($acount,2898,20497,'".AddSlashes(pg_result($resaco,$conresaco,'la41_faixaescolhida'))."','$this->la41_faixaescolhida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado em Número nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la41_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado em Número nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la41_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la41_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la41_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($la41_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16516,'$la41_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2898,16516,'','".AddSlashes(pg_result($resaco,$iresaco,'la41_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2898,16517,'','".AddSlashes(pg_result($resaco,$iresaco,'la41_i_result'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2898,16518,'','".AddSlashes(pg_result($resaco,$iresaco,'la41_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2898,20496,'','".AddSlashes(pg_result($resaco,$iresaco,'la41_valorpercentual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2898,20497,'','".AddSlashes(pg_result($resaco,$iresaco,'la41_faixaescolhida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_resultadonum
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la41_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la41_i_codigo = $la41_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado em Número nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la41_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado em Número nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la41_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la41_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_resultadonum";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la41_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_resultadonum ";
     $sql .= "      inner join lab_resultadoitem  on  lab_resultadoitem.la39_i_codigo = lab_resultadonum.la41_i_result";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_resultadoitem.la39_i_atributo";
     $sql2 = "";
     if($dbwhere==""){
       if($la41_i_codigo!=null ){
         $sql2 .= " where lab_resultadonum.la41_i_codigo = $la41_i_codigo "; 
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
   function sql_query_file ( $la41_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_resultadonum ";
     $sql2 = "";
     if($dbwhere==""){
       if($la41_i_codigo!=null ){
         $sql2 .= " where lab_resultadonum.la41_i_codigo = $la41_i_codigo "; 
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