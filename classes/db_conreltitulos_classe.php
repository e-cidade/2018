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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conreltitulos
class cl_conreltitulos { 
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
   var $c44_sequencia = 0; 
   var $c44_lei = null; 
   var $c44_quantidade = null; 
   var $c44_valemiss = 0; 
   var $c44_saldo = 0; 
   var $c44_movemiss = 0; 
   var $c44_movresgate = 0; 
   var $c44_saldoqtd = 0; 
   var $c44_saldovalor = 0; 
   var $c44_anousu = 0; 
   var $c44_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c44_sequencia = int4 = sequencia 
                 c44_lei = char(50) = Lei 
                 c44_quantidade = char(50) = Quantidade/Data 
                 c44_valemiss = float8 = Valor Emissao 
                 c44_saldo = float8 = Saldo  Anterior 
                 c44_movemiss = float8 = Emissão 
                 c44_movresgate = float8 = Resgate 
                 c44_saldoqtd = int4 = Quantidade proximo exercicio 
                 c44_saldovalor = float8 = Valor 
                 c44_anousu = int4 = Exercício 
                 c44_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_conreltitulos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conreltitulos"); 
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
       $this->c44_sequencia = ($this->c44_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_sequencia"]:$this->c44_sequencia);
       $this->c44_lei = ($this->c44_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_lei"]:$this->c44_lei);
       $this->c44_quantidade = ($this->c44_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_quantidade"]:$this->c44_quantidade);
       $this->c44_valemiss = ($this->c44_valemiss == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_valemiss"]:$this->c44_valemiss);
       $this->c44_saldo = ($this->c44_saldo == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_saldo"]:$this->c44_saldo);
       $this->c44_movemiss = ($this->c44_movemiss == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_movemiss"]:$this->c44_movemiss);
       $this->c44_movresgate = ($this->c44_movresgate == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_movresgate"]:$this->c44_movresgate);
       $this->c44_saldoqtd = ($this->c44_saldoqtd == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_saldoqtd"]:$this->c44_saldoqtd);
       $this->c44_saldovalor = ($this->c44_saldovalor == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_saldovalor"]:$this->c44_saldovalor);
       $this->c44_anousu = ($this->c44_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_anousu"]:$this->c44_anousu);
       $this->c44_instit = ($this->c44_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_instit"]:$this->c44_instit);
     }else{
       $this->c44_sequencia = ($this->c44_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["c44_sequencia"]:$this->c44_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($c44_sequencia){ 
      $this->atualizacampos();
     if($this->c44_lei == null ){ 
       $this->erro_sql = " Campo Lei nao Informado.";
       $this->erro_campo = "c44_lei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade/Data nao Informado.";
       $this->erro_campo = "c44_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_valemiss == null ){ 
       $this->erro_sql = " Campo Valor Emissao nao Informado.";
       $this->erro_campo = "c44_valemiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_saldo == null ){ 
       $this->erro_sql = " Campo Saldo  Anterior nao Informado.";
       $this->erro_campo = "c44_saldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_movemiss == null ){ 
       $this->erro_sql = " Campo Emissão nao Informado.";
       $this->erro_campo = "c44_movemiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_movresgate == null ){ 
       $this->erro_sql = " Campo Resgate nao Informado.";
       $this->erro_campo = "c44_movresgate";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_saldoqtd == null ){ 
       $this->erro_sql = " Campo Quantidade proximo exercicio nao Informado.";
       $this->erro_campo = "c44_saldoqtd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_saldovalor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "c44_saldovalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "c44_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c44_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "c44_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c44_sequencia == "" || $c44_sequencia == null ){
       $result = db_query("select nextval('conreltitulos_c44_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conreltitulos_c44_sequencia_seq do campo: c44_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c44_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conreltitulos_c44_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $c44_sequencia)){
         $this->erro_sql = " Campo c44_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c44_sequencia = $c44_sequencia; 
       }
     }
     if(($this->c44_sequencia == null) || ($this->c44_sequencia == "") ){ 
       $this->erro_sql = " Campo c44_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conreltitulos(
                                       c44_sequencia 
                                      ,c44_lei 
                                      ,c44_quantidade 
                                      ,c44_valemiss 
                                      ,c44_saldo 
                                      ,c44_movemiss 
                                      ,c44_movresgate 
                                      ,c44_saldoqtd 
                                      ,c44_saldovalor 
                                      ,c44_anousu 
                                      ,c44_instit 
                       )
                values (
                                $this->c44_sequencia 
                               ,'$this->c44_lei' 
                               ,'$this->c44_quantidade' 
                               ,$this->c44_valemiss 
                               ,$this->c44_saldo 
                               ,$this->c44_movemiss 
                               ,$this->c44_movresgate 
                               ,$this->c44_saldoqtd 
                               ,$this->c44_saldovalor 
                               ,$this->c44_anousu 
                               ,$this->c44_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->c44_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->c44_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c44_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c44_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8373,'$this->c44_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1419,8373,'','".AddSlashes(pg_result($resaco,0,'c44_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8374,'','".AddSlashes(pg_result($resaco,0,'c44_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8375,'','".AddSlashes(pg_result($resaco,0,'c44_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8376,'','".AddSlashes(pg_result($resaco,0,'c44_valemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8377,'','".AddSlashes(pg_result($resaco,0,'c44_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8378,'','".AddSlashes(pg_result($resaco,0,'c44_movemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8379,'','".AddSlashes(pg_result($resaco,0,'c44_movresgate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8380,'','".AddSlashes(pg_result($resaco,0,'c44_saldoqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8381,'','".AddSlashes(pg_result($resaco,0,'c44_saldovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8383,'','".AddSlashes(pg_result($resaco,0,'c44_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1419,8382,'','".AddSlashes(pg_result($resaco,0,'c44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c44_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update conreltitulos set ";
     $virgula = "";
     if(trim($this->c44_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_sequencia"])){ 
       $sql  .= $virgula." c44_sequencia = $this->c44_sequencia ";
       $virgula = ",";
       if(trim($this->c44_sequencia) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "c44_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_lei"])){ 
       $sql  .= $virgula." c44_lei = '$this->c44_lei' ";
       $virgula = ",";
       if(trim($this->c44_lei) == null ){ 
         $this->erro_sql = " Campo Lei nao Informado.";
         $this->erro_campo = "c44_lei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_quantidade"])){ 
       $sql  .= $virgula." c44_quantidade = '$this->c44_quantidade' ";
       $virgula = ",";
       if(trim($this->c44_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade/Data nao Informado.";
         $this->erro_campo = "c44_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_valemiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_valemiss"])){ 
       $sql  .= $virgula." c44_valemiss = $this->c44_valemiss ";
       $virgula = ",";
       if(trim($this->c44_valemiss) == null ){ 
         $this->erro_sql = " Campo Valor Emissao nao Informado.";
         $this->erro_campo = "c44_valemiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_saldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_saldo"])){ 
       $sql  .= $virgula." c44_saldo = $this->c44_saldo ";
       $virgula = ",";
       if(trim($this->c44_saldo) == null ){ 
         $this->erro_sql = " Campo Saldo  Anterior nao Informado.";
         $this->erro_campo = "c44_saldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_movemiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_movemiss"])){ 
       $sql  .= $virgula." c44_movemiss = $this->c44_movemiss ";
       $virgula = ",";
       if(trim($this->c44_movemiss) == null ){ 
         $this->erro_sql = " Campo Emissão nao Informado.";
         $this->erro_campo = "c44_movemiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_movresgate)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_movresgate"])){ 
       $sql  .= $virgula." c44_movresgate = $this->c44_movresgate ";
       $virgula = ",";
       if(trim($this->c44_movresgate) == null ){ 
         $this->erro_sql = " Campo Resgate nao Informado.";
         $this->erro_campo = "c44_movresgate";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_saldoqtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_saldoqtd"])){ 
       $sql  .= $virgula." c44_saldoqtd = $this->c44_saldoqtd ";
       $virgula = ",";
       if(trim($this->c44_saldoqtd) == null ){ 
         $this->erro_sql = " Campo Quantidade proximo exercicio nao Informado.";
         $this->erro_campo = "c44_saldoqtd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_saldovalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_saldovalor"])){ 
       $sql  .= $virgula." c44_saldovalor = $this->c44_saldovalor ";
       $virgula = ",";
       if(trim($this->c44_saldovalor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "c44_saldovalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_anousu"])){ 
       $sql  .= $virgula." c44_anousu = $this->c44_anousu ";
       $virgula = ",";
       if(trim($this->c44_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c44_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c44_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c44_instit"])){ 
       $sql  .= $virgula." c44_instit = $this->c44_instit ";
       $virgula = ",";
       if(trim($this->c44_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c44_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c44_sequencia!=null){
       $sql .= " c44_sequencia = $this->c44_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c44_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8373,'$this->c44_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1419,8373,'".AddSlashes(pg_result($resaco,$conresaco,'c44_sequencia'))."','$this->c44_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_lei"]))
           $resac = db_query("insert into db_acount values($acount,1419,8374,'".AddSlashes(pg_result($resaco,$conresaco,'c44_lei'))."','$this->c44_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_quantidade"]))
           $resac = db_query("insert into db_acount values($acount,1419,8375,'".AddSlashes(pg_result($resaco,$conresaco,'c44_quantidade'))."','$this->c44_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_valemiss"]))
           $resac = db_query("insert into db_acount values($acount,1419,8376,'".AddSlashes(pg_result($resaco,$conresaco,'c44_valemiss'))."','$this->c44_valemiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_saldo"]))
           $resac = db_query("insert into db_acount values($acount,1419,8377,'".AddSlashes(pg_result($resaco,$conresaco,'c44_saldo'))."','$this->c44_saldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_movemiss"]))
           $resac = db_query("insert into db_acount values($acount,1419,8378,'".AddSlashes(pg_result($resaco,$conresaco,'c44_movemiss'))."','$this->c44_movemiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_movresgate"]))
           $resac = db_query("insert into db_acount values($acount,1419,8379,'".AddSlashes(pg_result($resaco,$conresaco,'c44_movresgate'))."','$this->c44_movresgate',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_saldoqtd"]))
           $resac = db_query("insert into db_acount values($acount,1419,8380,'".AddSlashes(pg_result($resaco,$conresaco,'c44_saldoqtd'))."','$this->c44_saldoqtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_saldovalor"]))
           $resac = db_query("insert into db_acount values($acount,1419,8381,'".AddSlashes(pg_result($resaco,$conresaco,'c44_saldovalor'))."','$this->c44_saldovalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1419,8383,'".AddSlashes(pg_result($resaco,$conresaco,'c44_anousu'))."','$this->c44_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c44_instit"]))
           $resac = db_query("insert into db_acount values($acount,1419,8382,'".AddSlashes(pg_result($resaco,$conresaco,'c44_instit'))."','$this->c44_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c44_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c44_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c44_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c44_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c44_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8373,'$c44_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1419,8373,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8374,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8375,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8376,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_valemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8377,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_saldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8378,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_movemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8379,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_movresgate'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8380,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_saldoqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8381,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_saldovalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8383,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1419,8382,'','".AddSlashes(pg_result($resaco,$iresaco,'c44_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conreltitulos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c44_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c44_sequencia = $c44_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c44_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c44_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c44_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:conreltitulos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c44_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conreltitulos ";
     $sql .= "      inner join db_config  on  db_config.codigo = conreltitulos.c44_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c44_sequencia!=null ){
         $sql2 .= " where conreltitulos.c44_sequencia = $c44_sequencia "; 
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
   function sql_query_file ( $c44_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conreltitulos ";
     $sql2 = "";
     if($dbwhere==""){
       if($c44_sequencia!=null ){
         $sql2 .= " where conreltitulos.c44_sequencia = $c44_sequencia "; 
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