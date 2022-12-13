<?
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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdebitosprocreg
class cl_cancdebitosprocreg { 
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
   var $k24_sequencia = 0; 
   var $k24_codigo = 0; 
   var $k24_cancdebitosreg = 0; 
   var $k24_vlrhis = 0; 
   var $k24_vlrcor = 0; 
   var $k24_juros = 0; 
   var $k24_multa = 0; 
   var $k24_desconto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k24_sequencia = int4 = Sequencia 
                 k24_codigo = int4 = Codigo 
                 k24_cancdebitosreg = int4 = Cancela Debitos 
                 k24_vlrhis = float8 = Valor Historico 
                 k24_vlrcor = float8 = Valor Corrigido 
                 k24_juros = float8 = Juros 
                 k24_multa = float8 = Multa 
                 k24_desconto = float8 = Desconto 
                 ";
   //funcao construtor da classe 
   function cl_cancdebitosprocreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitosprocreg"); 
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
       $this->k24_sequencia = ($this->k24_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_sequencia"]:$this->k24_sequencia);
       $this->k24_codigo = ($this->k24_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_codigo"]:$this->k24_codigo);
       $this->k24_cancdebitosreg = ($this->k24_cancdebitosreg == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_cancdebitosreg"]:$this->k24_cancdebitosreg);
       $this->k24_vlrhis = ($this->k24_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_vlrhis"]:$this->k24_vlrhis);
       $this->k24_vlrcor = ($this->k24_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_vlrcor"]:$this->k24_vlrcor);
       $this->k24_juros = ($this->k24_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_juros"]:$this->k24_juros);
       $this->k24_multa = ($this->k24_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_multa"]:$this->k24_multa);
       $this->k24_desconto = ($this->k24_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_desconto"]:$this->k24_desconto);
     }else{
       $this->k24_sequencia = ($this->k24_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k24_sequencia"]:$this->k24_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($k24_sequencia){ 
      $this->atualizacampos();
     if($this->k24_codigo == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "k24_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_cancdebitosreg == null ){ 
       $this->erro_sql = " Campo Cancela Debitos nao Informado.";
       $this->erro_campo = "k24_cancdebitosreg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_vlrhis == null ){ 
       $this->erro_sql = " Campo Valor Historico nao Informado.";
       $this->erro_campo = "k24_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "k24_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k24_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k24_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k24_desconto == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "k24_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k24_sequencia == "" || $k24_sequencia == null ){
       $result = db_query("select nextval('cancdebitosprocreg_k24_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitosprocreg_k24_sequencia_seq do campo: k24_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k24_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdebitosprocreg_k24_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $k24_sequencia)){
         $this->erro_sql = " Campo k24_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k24_sequencia = $k24_sequencia; 
       }
     }
     if(($this->k24_sequencia == null) || ($this->k24_sequencia == "") ){ 
       $this->erro_sql = " Campo k24_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitosprocreg(
                                       k24_sequencia 
                                      ,k24_codigo 
                                      ,k24_cancdebitosreg 
                                      ,k24_vlrhis 
                                      ,k24_vlrcor 
                                      ,k24_juros 
                                      ,k24_multa 
                                      ,k24_desconto 
                       )
                values (
                                $this->k24_sequencia 
                               ,$this->k24_codigo 
                               ,$this->k24_cancdebitosreg 
                               ,$this->k24_vlrhis 
                               ,$this->k24_vlrcor 
                               ,$this->k24_juros 
                               ,$this->k24_multa 
                               ,$this->k24_desconto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cancdebitosprocreg ($this->k24_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cancdebitosprocreg já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cancdebitosprocreg ($this->k24_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k24_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k24_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7416,'$this->k24_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1234,7416,'','".AddSlashes(pg_result($resaco,0,'k24_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7417,'','".AddSlashes(pg_result($resaco,0,'k24_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7418,'','".AddSlashes(pg_result($resaco,0,'k24_cancdebitosreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7419,'','".AddSlashes(pg_result($resaco,0,'k24_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7420,'','".AddSlashes(pg_result($resaco,0,'k24_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7421,'','".AddSlashes(pg_result($resaco,0,'k24_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7422,'','".AddSlashes(pg_result($resaco,0,'k24_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1234,7423,'','".AddSlashes(pg_result($resaco,0,'k24_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k24_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update cancdebitosprocreg set ";
     $virgula = "";
     if(trim($this->k24_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_sequencia"])){ 
       $sql  .= $virgula." k24_sequencia = $this->k24_sequencia ";
       $virgula = ",";
       if(trim($this->k24_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "k24_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_codigo"])){ 
       $sql  .= $virgula." k24_codigo = $this->k24_codigo ";
       $virgula = ",";
       if(trim($this->k24_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "k24_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_cancdebitosreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_cancdebitosreg"])){ 
       $sql  .= $virgula." k24_cancdebitosreg = $this->k24_cancdebitosreg ";
       $virgula = ",";
       if(trim($this->k24_cancdebitosreg) == null ){ 
         $this->erro_sql = " Campo Cancela Debitos nao Informado.";
         $this->erro_campo = "k24_cancdebitosreg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_vlrhis"])){ 
       $sql  .= $virgula." k24_vlrhis = $this->k24_vlrhis ";
       $virgula = ",";
       if(trim($this->k24_vlrhis) == null ){ 
         $this->erro_sql = " Campo Valor Historico nao Informado.";
         $this->erro_campo = "k24_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_vlrcor"])){ 
       $sql  .= $virgula." k24_vlrcor = $this->k24_vlrcor ";
       $virgula = ",";
       if(trim($this->k24_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "k24_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_juros"])){ 
       $sql  .= $virgula." k24_juros = $this->k24_juros ";
       $virgula = ",";
       if(trim($this->k24_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k24_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_multa"])){ 
       $sql  .= $virgula." k24_multa = $this->k24_multa ";
       $virgula = ",";
       if(trim($this->k24_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k24_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k24_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k24_desconto"])){ 
       $sql  .= $virgula." k24_desconto = $this->k24_desconto ";
       $virgula = ",";
       if(trim($this->k24_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "k24_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k24_sequencia!=null){
       $sql .= " k24_sequencia = $this->k24_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k24_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7416,'$this->k24_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1234,7416,'".AddSlashes(pg_result($resaco,$conresaco,'k24_sequencia'))."','$this->k24_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1234,7417,'".AddSlashes(pg_result($resaco,$conresaco,'k24_codigo'))."','$this->k24_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_cancdebitosreg"]))
           $resac = db_query("insert into db_acount values($acount,1234,7418,'".AddSlashes(pg_result($resaco,$conresaco,'k24_cancdebitosreg'))."','$this->k24_cancdebitosreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_vlrhis"]))
           $resac = db_query("insert into db_acount values($acount,1234,7419,'".AddSlashes(pg_result($resaco,$conresaco,'k24_vlrhis'))."','$this->k24_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,1234,7420,'".AddSlashes(pg_result($resaco,$conresaco,'k24_vlrcor'))."','$this->k24_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_juros"]))
           $resac = db_query("insert into db_acount values($acount,1234,7421,'".AddSlashes(pg_result($resaco,$conresaco,'k24_juros'))."','$this->k24_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_multa"]))
           $resac = db_query("insert into db_acount values($acount,1234,7422,'".AddSlashes(pg_result($resaco,$conresaco,'k24_multa'))."','$this->k24_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k24_desconto"]))
           $resac = db_query("insert into db_acount values($acount,1234,7423,'".AddSlashes(pg_result($resaco,$conresaco,'k24_desconto'))."','$this->k24_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosprocreg nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k24_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosprocreg nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k24_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k24_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k24_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k24_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7416,'$k24_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1234,7416,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7417,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7418,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_cancdebitosreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7419,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7420,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7421,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7422,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1234,7423,'','".AddSlashes(pg_result($resaco,$iresaco,'k24_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitosprocreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k24_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k24_sequencia = $k24_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosprocreg nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k24_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosprocreg nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k24_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k24_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitosprocreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k24_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosprocreg ";
     $sql .= "      inner join cancdebitosreg  on  cancdebitosreg.k21_sequencia = cancdebitosprocreg.k24_cancdebitosreg";
     $sql .= "      inner join cancdebitosproc on  cancdebitosproc.k23_codigo = cancdebitosprocreg.k24_codigo";
     $sql .= "      inner join cancdebitos     on  cancdebitos.k20_codigo = cancdebitosreg.k21_codigo";
     $sql .= "                                and  cancdebitos.k20_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join db_usuarios     on  db_usuarios.id_usuario = cancdebitosproc.k23_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k24_sequencia!=null ){
         $sql2 .= " where cancdebitosprocreg.k24_sequencia = $k24_sequencia "; 
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
   function sql_query_file ( $k24_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosprocreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k24_sequencia!=null ){
         $sql2 .= " where cancdebitosprocreg.k24_sequencia = $k24_sequencia "; 
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