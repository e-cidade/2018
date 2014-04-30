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

//MODULO: biblioteca
//CLASSE DA ENTIDADE emprestimo
class cl_emprestimo { 
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
   var $bi18_codigo = 0; 
   var $bi18_retirada_dia = null; 
   var $bi18_retirada_mes = null; 
   var $bi18_retirada_ano = null; 
   var $bi18_retirada = null; 
   var $bi18_devolucao_dia = null; 
   var $bi18_devolucao_mes = null; 
   var $bi18_devolucao_ano = null; 
   var $bi18_devolucao = null; 
   var $bi18_carteira = 0; 
   var $bi18_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi18_codigo = int8 = Código 
                 bi18_retirada = date = Datade retirada 
                 bi18_devolucao = date = Data de devolução 
                 bi18_carteira = int8 = Carteira 
                 bi18_usuario = int8 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_emprestimo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("emprestimo"); 
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
       $this->bi18_codigo = ($this->bi18_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_codigo"]:$this->bi18_codigo);
       if($this->bi18_retirada == ""){
         $this->bi18_retirada_dia = ($this->bi18_retirada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_retirada_dia"]:$this->bi18_retirada_dia);
         $this->bi18_retirada_mes = ($this->bi18_retirada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_retirada_mes"]:$this->bi18_retirada_mes);
         $this->bi18_retirada_ano = ($this->bi18_retirada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_retirada_ano"]:$this->bi18_retirada_ano);
         if($this->bi18_retirada_dia != ""){
            $this->bi18_retirada = $this->bi18_retirada_ano."-".$this->bi18_retirada_mes."-".$this->bi18_retirada_dia;
         }
       }
       if($this->bi18_devolucao == ""){
         $this->bi18_devolucao_dia = ($this->bi18_devolucao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_dia"]:$this->bi18_devolucao_dia);
         $this->bi18_devolucao_mes = ($this->bi18_devolucao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_mes"]:$this->bi18_devolucao_mes);
         $this->bi18_devolucao_ano = ($this->bi18_devolucao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_ano"]:$this->bi18_devolucao_ano);
         if($this->bi18_devolucao_dia != ""){
            $this->bi18_devolucao = $this->bi18_devolucao_ano."-".$this->bi18_devolucao_mes."-".$this->bi18_devolucao_dia;
         }
       }
       $this->bi18_carteira = ($this->bi18_carteira == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_carteira"]:$this->bi18_carteira);
       $this->bi18_usuario = ($this->bi18_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_usuario"]:$this->bi18_usuario);
     }else{
       $this->bi18_codigo = ($this->bi18_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi18_codigo"]:$this->bi18_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi18_codigo){ 
      $this->atualizacampos();
     if($this->bi18_retirada == null ){ 
       $this->erro_sql = " Campo Datade retirada nao Informado.";
       $this->erro_campo = "bi18_retirada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi18_devolucao == null ){ 
       $this->erro_sql = " Campo Data de devolução nao Informado.";
       $this->erro_campo = "bi18_devolucao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi18_carteira == null ){ 
       $this->erro_sql = " Campo Carteira nao Informado.";
       $this->erro_campo = "bi18_carteira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi18_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "bi18_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi18_codigo == "" || $bi18_codigo == null ){
       $result = db_query("select nextval('emprestimo_bi18_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: emprestimo_bi18_codigo_seq do campo: bi18_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bi18_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from emprestimo_bi18_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi18_codigo)){
         $this->erro_sql = " Campo bi18_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi18_codigo = $bi18_codigo; 
       }
     }
     if(($this->bi18_codigo == null) || ($this->bi18_codigo == "") ){ 
       $this->erro_sql = " Campo bi18_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into emprestimo(
                                       bi18_codigo 
                                      ,bi18_retirada 
                                      ,bi18_devolucao 
                                      ,bi18_carteira 
                                      ,bi18_usuario 
                       )
                values (
                                $this->bi18_codigo 
                               ,".($this->bi18_retirada == "null" || $this->bi18_retirada == ""?"null":"'".$this->bi18_retirada."'")." 
                               ,".($this->bi18_devolucao == "null" || $this->bi18_devolucao == ""?"null":"'".$this->bi18_devolucao."'")." 
                               ,$this->bi18_carteira 
                               ,$this->bi18_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empréstimo ($this->bi18_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empréstimo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empréstimo ($this->bi18_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi18_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi18_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008129,'$this->bi18_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1008023,1008129,'','".AddSlashes(pg_result($resaco,0,'bi18_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008023,1008130,'','".AddSlashes(pg_result($resaco,0,'bi18_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008023,1008131,'','".AddSlashes(pg_result($resaco,0,'bi18_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008023,1008132,'','".AddSlashes(pg_result($resaco,0,'bi18_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008023,1008935,'','".AddSlashes(pg_result($resaco,0,'bi18_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bi18_codigo=null) { 
      $this->atualizacampos();
     $sql = " update emprestimo set ";
     $virgula = "";
     if(trim($this->bi18_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi18_codigo"])){ 
       $sql  .= $virgula." bi18_codigo = $this->bi18_codigo ";
       $virgula = ",";
       if(trim($this->bi18_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "bi18_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi18_retirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi18_retirada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi18_retirada_dia"] !="") ){ 
       $sql  .= $virgula." bi18_retirada = '$this->bi18_retirada' ";
       $virgula = ",";
       if(trim($this->bi18_retirada) == null ){ 
         $this->erro_sql = " Campo Datade retirada nao Informado.";
         $this->erro_campo = "bi18_retirada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_retirada_dia"])){ 
         $sql  .= $virgula." bi18_retirada = null ";
         $virgula = ",";
         if(trim($this->bi18_retirada) == null ){ 
           $this->erro_sql = " Campo Datade retirada nao Informado.";
           $this->erro_campo = "bi18_retirada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi18_devolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_dia"] !="") ){ 
       $sql  .= $virgula." bi18_devolucao = '$this->bi18_devolucao' ";
       $virgula = ",";
       if(trim($this->bi18_devolucao) == null ){ 
         $this->erro_sql = " Campo Data de devolução nao Informado.";
         $this->erro_campo = "bi18_devolucao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_devolucao_dia"])){ 
         $sql  .= $virgula." bi18_devolucao = null ";
         $virgula = ",";
         if(trim($this->bi18_devolucao) == null ){ 
           $this->erro_sql = " Campo Data de devolução nao Informado.";
           $this->erro_campo = "bi18_devolucao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi18_carteira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi18_carteira"])){ 
       $sql  .= $virgula." bi18_carteira = $this->bi18_carteira ";
       $virgula = ",";
       if(trim($this->bi18_carteira) == null ){ 
         $this->erro_sql = " Campo Carteira nao Informado.";
         $this->erro_campo = "bi18_carteira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi18_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi18_usuario"])){ 
       $sql  .= $virgula." bi18_usuario = $this->bi18_usuario ";
       $virgula = ",";
       if(trim($this->bi18_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "bi18_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi18_codigo!=null){
       $sql .= " bi18_codigo = $this->bi18_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi18_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008129,'$this->bi18_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1008023,1008129,'".AddSlashes(pg_result($resaco,$conresaco,'bi18_codigo'))."','$this->bi18_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_retirada"]))
           $resac = db_query("insert into db_acount values($acount,1008023,1008130,'".AddSlashes(pg_result($resaco,$conresaco,'bi18_retirada'))."','$this->bi18_retirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_devolucao"]))
           $resac = db_query("insert into db_acount values($acount,1008023,1008131,'".AddSlashes(pg_result($resaco,$conresaco,'bi18_devolucao'))."','$this->bi18_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_carteira"]))
           $resac = db_query("insert into db_acount values($acount,1008023,1008132,'".AddSlashes(pg_result($resaco,$conresaco,'bi18_carteira'))."','$this->bi18_carteira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi18_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1008023,1008935,'".AddSlashes(pg_result($resaco,$conresaco,'bi18_usuario'))."','$this->bi18_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empréstimo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi18_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empréstimo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi18_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi18_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bi18_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi18_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008129,'$bi18_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1008023,1008129,'','".AddSlashes(pg_result($resaco,$iresaco,'bi18_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008023,1008130,'','".AddSlashes(pg_result($resaco,$iresaco,'bi18_retirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008023,1008131,'','".AddSlashes(pg_result($resaco,$iresaco,'bi18_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008023,1008132,'','".AddSlashes(pg_result($resaco,$iresaco,'bi18_carteira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008023,1008935,'','".AddSlashes(pg_result($resaco,$iresaco,'bi18_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from emprestimo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi18_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi18_codigo = $bi18_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empréstimo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi18_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empréstimo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi18_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi18_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:emprestimo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bi18_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emprestimo ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emprestimo.bi18_usuario";
     $sql .= "      inner join carteira  on  carteira.bi16_codigo = emprestimo.bi18_carteira";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = carteira.bi16_usuario";
     $sql .= "      inner join leitorcategoria  on  leitorcategoria.bi07_codigo = carteira.bi16_leitorcategoria";
     $sql .= "      inner join leitor  on  leitor.bi10_codigo = carteira.bi16_leitor";
     $sql .= "      left join leitoraluno on leitoraluno.bi11_leitor = leitor.bi10_codigo";
     $sql .= "      left join aluno on aluno.ed47_i_codigo = leitoraluno.bi11_aluno";
     $sql .= "      left join alunocurso on alunocurso.ed56_i_aluno = ed47_i_codigo";
     $sql .= "      left join leitorfunc on leitorfunc.bi12_leitor = leitor.bi10_codigo";
     $sql .= "      left join rhpessoal on rhpessoal.rh01_regist = leitorfunc.bi12_rechumano";
     $sql .= "      left join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join leitorpublico on leitorpublico.bi13_leitor = leitor.bi10_codigo";
     $sql .= "      left join cgm as cgmpub on cgmpub.z01_numcgm = leitorpublico.bi13_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($bi18_codigo!=null ){
         $sql2 .= " where emprestimo.bi18_codigo = $bi18_codigo "; 
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
   function sql_query_file ( $bi18_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emprestimo ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi18_codigo!=null ){
         $sql2 .= " where emprestimo.bi18_codigo = $bi18_codigo "; 
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