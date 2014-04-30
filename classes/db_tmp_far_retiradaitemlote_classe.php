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

//MODULO: Farmácia
//CLASSE DA ENTIDADE far_retiradaitemlote
class cl_tmp_far_retiradaitemlote { 
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
   var $fa09_i_codigo = 0; 
   var $fa09_i_retiradaitens = 0; 
   var $fa09_i_matestoqueitem = 0; 
   var $fa09_f_quant = 0; 
   //Nome das tabelas temporárias
   var $tmp_far_retirada = null;
   var $tmp_far_retiradaitens = null;
   var $tmp_far_retiradaitemlote = null;
   var $tmp_far_retiradarequisitante = null;
   var $tmp_far_retiradarequi = null;
   
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa09_i_codigo = int4 = Código 
                 fa09_i_retiradaitens = int4 = Retirada itens 
                 fa09_i_matestoqueitem = int4 = Matestoqueitem 
                 fa09_f_quant = float4 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_tmp_far_retiradaitemlote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tmp_far_retiradaitemlote"); 
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
       $this->fa09_i_codigo = ($this->fa09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa09_i_codigo"]:$this->fa09_i_codigo);
       $this->fa09_i_retiradaitens = ($this->fa09_i_retiradaitens == ""?@$GLOBALS["HTTP_POST_VARS"]["fa09_i_retiradaitens"]:$this->fa09_i_retiradaitens);
       $this->fa09_i_matestoqueitem = ($this->fa09_i_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["fa09_i_matestoqueitem"]:$this->fa09_i_matestoqueitem);
       $this->fa09_f_quant = ($this->fa09_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["fa09_f_quant"]:$this->fa09_f_quant);
     }else{
       $this->fa09_i_codigo = ($this->fa09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa09_i_codigo"]:$this->fa09_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa09_i_codigo){ 
      $this->atualizacampos();
     if($this->fa09_i_retiradaitens == null ){ 
       $this->erro_sql = " Campo Retirada itens nao Informado.";
       $this->erro_campo = "fa09_i_retiradaitens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa09_i_matestoqueitem == null ){ 
       $this->erro_sql = " Campo Matestoqueitem nao Informado.";
       $this->erro_campo = "fa09_i_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa09_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "fa09_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa09_i_codigo == "" || $fa09_i_codigo == null ){
       $result = @pg_query("select nextval('faretiradaitemlote_fa09_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: faretiradaitemlote_fa09_i_codigo_seq do campo: fa09_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa09_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from faretiradaitemlote_fa09_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa09_i_codigo)){
         $this->erro_sql = " Campo fa09_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa09_i_codigo = $fa09_i_codigo; 
       }
     }
     if(($this->fa09_i_codigo == null) || ($this->fa09_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa09_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ".$this->tmp_far_retiradaitemlote."(
                                       fa09_i_codigo 
                                      ,fa09_i_retiradaitens 
                                      ,fa09_i_matestoqueitem 
                                      ,fa09_f_quant 
                       )
                values (
                                $this->fa09_i_codigo 
                               ,$this->fa09_i_retiradaitens 
                               ,$this->fa09_i_matestoqueitem 
                               ,$this->fa09_f_quant 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "".$this->tmp_far_retiradaitemlote." ($this->fa09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "".$this->tmp_far_retiradaitemlote." já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "".$this->tmp_far_retiradaitemlote." ($this->fa09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa09_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa09_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,12255,'$this->fa09_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,2131,12255,'','".AddSlashes(pg_result($resaco,0,'fa09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2131,12256,'','".AddSlashes(pg_result($resaco,0,'fa09_i_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2131,12260,'','".AddSlashes(pg_result($resaco,0,'fa09_i_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2131,12257,'','".AddSlashes(pg_result($resaco,0,'fa09_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa09_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ".$this->tmp_far_retiradaitemlote." set ";
     $virgula = "";
     if(trim($this->fa09_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_codigo"])){ 
       $sql  .= $virgula." fa09_i_codigo = $this->fa09_i_codigo ";
       $virgula = ",";
       if(trim($this->fa09_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa09_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa09_i_retiradaitens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_retiradaitens"])){ 
       $sql  .= $virgula." fa09_i_retiradaitens = $this->fa09_i_retiradaitens ";
       $virgula = ",";
       if(trim($this->fa09_i_retiradaitens) == null ){ 
         $this->erro_sql = " Campo Retirada itens nao Informado.";
         $this->erro_campo = "fa09_i_retiradaitens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa09_i_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_matestoqueitem"])){ 
       $sql  .= $virgula." fa09_i_matestoqueitem = $this->fa09_i_matestoqueitem ";
       $virgula = ",";
       if(trim($this->fa09_i_matestoqueitem) == null ){ 
         $this->erro_sql = " Campo Matestoqueitem nao Informado.";
         $this->erro_campo = "fa09_i_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa09_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa09_f_quant"])){ 
       $sql  .= $virgula." fa09_f_quant = $this->fa09_f_quant ";
       $virgula = ",";
       if(trim($this->fa09_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "fa09_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa09_i_codigo!=null){
       $sql .= " fa09_i_codigo = $this->fa09_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa09_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,12255,'$this->fa09_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,2131,12255,'".AddSlashes(pg_result($resaco,$conresaco,'fa09_i_codigo'))."','$this->fa09_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_retiradaitens"]))
           $resac = pg_query("insert into db_acount values($acount,2131,12256,'".AddSlashes(pg_result($resaco,$conresaco,'fa09_i_retiradaitens'))."','$this->fa09_i_retiradaitens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa09_i_matestoqueitem"]))
           $resac = pg_query("insert into db_acount values($acount,2131,12260,'".AddSlashes(pg_result($resaco,$conresaco,'fa09_i_matestoqueitem'))."','$this->fa09_i_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa09_f_quant"]))
           $resac = pg_query("insert into db_acount values($acount,2131,12257,'".AddSlashes(pg_result($resaco,$conresaco,'fa09_f_quant'))."','$this->fa09_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "".$this->tmp_far_retiradaitemlote." nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "".$this->tmp_far_retiradaitemlote." nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa09_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa09_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,12255,'$fa09_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,2131,12255,'','".AddSlashes(pg_result($resaco,$iresaco,'fa09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2131,12256,'','".AddSlashes(pg_result($resaco,$iresaco,'fa09_i_retiradaitens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2131,12260,'','".AddSlashes(pg_result($resaco,$iresaco,'fa09_i_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2131,12257,'','".AddSlashes(pg_result($resaco,$iresaco,'fa09_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ".$this->tmp_far_retiradaitemlote."
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa09_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa09_i_codigo = $fa09_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "".$this->tmp_far_retiradaitemlote." nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "".$this->tmp_far_retiradaitemlote." nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa09_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na ".$this->tmp_far_retiradaitemlote."";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_far_retiradaitemlote." ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = ".$this->tmp_far_retiradaitemlote.".fa09_i_matestoqueitem";
     $sql .= "      inner join ".$this->tmp_far_retiradaitens."  on  ".$this->tmp_far_retiradaitens.".fa06_i_codigo = ".$this->tmp_far_retiradaitemlote.".fa09_i_retiradaitens";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join far_matersaude  on  far_matersaude.fa01_i_codigo = ".$this->tmp_far_retiradaitens.".fa06_i_matersaude";
     $sql .= "      inner join ".$this->tmp_far_retirada."  as a on   a.fa04_i_codigo = ".$this->tmp_far_retiradaitens.".fa06_i_retirada";
     $sql2 = "";
     if($dbwhere==""){
       if($fa09_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_far_retiradaitemlote.".fa09_i_codigo = $fa09_i_codigo "; 
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
   function sql_query_file ( $fa09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ".$this->tmp_far_retiradaitemlote." ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa09_i_codigo!=null ){
         $sql2 .= " where ".$this->tmp_far_retiradaitemlote.".fa09_i_codigo = $fa09_i_codigo "; 
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