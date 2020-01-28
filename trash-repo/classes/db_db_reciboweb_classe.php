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

//MODULO: Caixa
//CLASSE DA ENTIDADE db_reciboweb
class cl_db_reciboweb { 
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
   var $k99_numpre = 0; 
   var $k99_numpar = 0; 
   var $k99_numpre_n = 0; 
   var $k99_codbco = 0; 
   var $k99_codage = null; 
   var $k99_numbco = null; 
   var $k99_desconto = 0; 
   var $k99_tipo = 0; 
   var $k99_origem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k99_numpre = int4 = Código Numpre 
                 k99_numpar = int4 = Parcela Numpre 
                 k99_numpre_n = int4 = Codigo Novo 
                 k99_codbco = int4 = Código do banco 
                 k99_codage = char(5) = Código da Agencia 
                 k99_numbco = varchar(15) = Numero do banco 
                 k99_desconto = int4 = Desconto 
                 k99_tipo = int4 = Tipo do Recibo 
                 k99_origem = int4 = Origem da Geração 
                 ";
   //funcao construtor da classe 
   function cl_db_reciboweb() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_reciboweb"); 
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
       $this->k99_numpre = ($this->k99_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpre"]:$this->k99_numpre);
       $this->k99_numpar = ($this->k99_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpar"]:$this->k99_numpar);
       $this->k99_numpre_n = ($this->k99_numpre_n == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpre_n"]:$this->k99_numpre_n);
       $this->k99_codbco = ($this->k99_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_codbco"]:$this->k99_codbco);
       $this->k99_codage = ($this->k99_codage == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_codage"]:$this->k99_codage);
       $this->k99_numbco = ($this->k99_numbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numbco"]:$this->k99_numbco);
       $this->k99_desconto = ($this->k99_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_desconto"]:$this->k99_desconto);
       $this->k99_tipo = ($this->k99_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_tipo"]:$this->k99_tipo);
       $this->k99_origem = ($this->k99_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_origem"]:$this->k99_origem);
     }else{
       $this->k99_numpre = ($this->k99_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpre"]:$this->k99_numpre);
       $this->k99_numpar = ($this->k99_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpar"]:$this->k99_numpar);
       $this->k99_numpre_n = ($this->k99_numpre_n == ""?@$GLOBALS["HTTP_POST_VARS"]["k99_numpre_n"]:$this->k99_numpre_n);
     }
   }
   // funcao para inclusao
   function incluir ($k99_numpre,$k99_numpar,$k99_numpre_n){ 
      $this->atualizacampos();
     if($this->k99_codbco == null ){ 
       $this->erro_sql = " Campo Código do banco nao Informado.";
       $this->erro_campo = "k99_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k99_codage == null ){ 
       $this->erro_sql = " Campo Código da Agencia nao Informado.";
       $this->erro_campo = "k99_codage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k99_numbco == null ){ 
       $this->erro_sql = " Campo Numero do banco nao Informado.";
       $this->erro_campo = "k99_numbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k99_desconto == null ){ 
       $this->k99_desconto = "0";
     }
     if($this->k99_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do Recibo nao Informado.";
       $this->erro_campo = "k99_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k99_origem == null ){ 
       $this->erro_sql = " Campo Origem da Geração nao Informado.";
       $this->erro_campo = "k99_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k99_numpre = $k99_numpre; 
       $this->k99_numpar = $k99_numpar; 
       $this->k99_numpre_n = $k99_numpre_n; 
     if(($this->k99_numpre == null) || ($this->k99_numpre == "") ){ 
       $this->erro_sql = " Campo k99_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k99_numpar == null) || ($this->k99_numpar == "") ){ 
       $this->erro_sql = " Campo k99_numpar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k99_numpre_n == null) || ($this->k99_numpre_n == "") ){ 
       $this->erro_sql = " Campo k99_numpre_n nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_reciboweb(
                                       k99_numpre 
                                      ,k99_numpar 
                                      ,k99_numpre_n 
                                      ,k99_codbco 
                                      ,k99_codage 
                                      ,k99_numbco 
                                      ,k99_desconto 
                                      ,k99_tipo 
                                      ,k99_origem 
                       )
                values (
                                $this->k99_numpre 
                               ,$this->k99_numpar 
                               ,$this->k99_numpre_n 
                               ,$this->k99_codbco 
                               ,'$this->k99_codage' 
                               ,'$this->k99_numbco' 
                               ,$this->k99_desconto 
                               ,$this->k99_tipo 
                               ,$this->k99_origem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Recibo Emitidos ($this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Recibo Emitidos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Recibo Emitidos ($this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k99_numpre,$this->k99_numpar,$this->k99_numpre_n));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1167,'$this->k99_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,1168,'$this->k99_numpar','I')");
       $resac = db_query("insert into db_acountkey values($acount,1169,'$this->k99_numpre_n','I')");
       $resac = db_query("insert into db_acount values($acount,210,1167,'','".AddSlashes(pg_result($resaco,0,'k99_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,1168,'','".AddSlashes(pg_result($resaco,0,'k99_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,1169,'','".AddSlashes(pg_result($resaco,0,'k99_numpre_n'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,1170,'','".AddSlashes(pg_result($resaco,0,'k99_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,1171,'','".AddSlashes(pg_result($resaco,0,'k99_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,1172,'','".AddSlashes(pg_result($resaco,0,'k99_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,6140,'','".AddSlashes(pg_result($resaco,0,'k99_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,10811,'','".AddSlashes(pg_result($resaco,0,'k99_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,210,10812,'','".AddSlashes(pg_result($resaco,0,'k99_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k99_numpre=null,$k99_numpar=null,$k99_numpre_n=null) { 
      $this->atualizacampos();
     $sql = " update db_reciboweb set ";
     $virgula = "";
     if(trim($this->k99_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_numpre"])){ 
       $sql  .= $virgula." k99_numpre = $this->k99_numpre ";
       $virgula = ",";
       if(trim($this->k99_numpre) == null ){ 
         $this->erro_sql = " Campo Código Numpre nao Informado.";
         $this->erro_campo = "k99_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_numpar"])){ 
       $sql  .= $virgula." k99_numpar = $this->k99_numpar ";
       $virgula = ",";
       if(trim($this->k99_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela Numpre nao Informado.";
         $this->erro_campo = "k99_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_numpre_n)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_numpre_n"])){ 
       $sql  .= $virgula." k99_numpre_n = $this->k99_numpre_n ";
       $virgula = ",";
       if(trim($this->k99_numpre_n) == null ){ 
         $this->erro_sql = " Campo Codigo Novo nao Informado.";
         $this->erro_campo = "k99_numpre_n";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_codbco"])){ 
       $sql  .= $virgula." k99_codbco = $this->k99_codbco ";
       $virgula = ",";
       if(trim($this->k99_codbco) == null ){ 
         $this->erro_sql = " Campo Código do banco nao Informado.";
         $this->erro_campo = "k99_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_codage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_codage"])){ 
       $sql  .= $virgula." k99_codage = '$this->k99_codage' ";
       $virgula = ",";
       if(trim($this->k99_codage) == null ){ 
         $this->erro_sql = " Campo Código da Agencia nao Informado.";
         $this->erro_campo = "k99_codage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_numbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_numbco"])){ 
       $sql  .= $virgula." k99_numbco = '$this->k99_numbco' ";
       $virgula = ",";
       if(trim($this->k99_numbco) == null ){ 
         $this->erro_sql = " Campo Numero do banco nao Informado.";
         $this->erro_campo = "k99_numbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_desconto"])){ 
        if(trim($this->k99_desconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k99_desconto"])){ 
           $this->k99_desconto = "0" ; 
        } 
       $sql  .= $virgula." k99_desconto = $this->k99_desconto ";
       $virgula = ",";
     }
     if(trim($this->k99_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_tipo"])){ 
       $sql  .= $virgula." k99_tipo = $this->k99_tipo ";
       $virgula = ",";
       if(trim($this->k99_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do Recibo nao Informado.";
         $this->erro_campo = "k99_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k99_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k99_origem"])){ 
       $sql  .= $virgula." k99_origem = $this->k99_origem ";
       $virgula = ",";
       if(trim($this->k99_origem) == null ){ 
         $this->erro_sql = " Campo Origem da Geração nao Informado.";
         $this->erro_campo = "k99_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k99_numpre!=null){
       $sql .= " k99_numpre = $this->k99_numpre";
     }
     if($k99_numpar!=null){
       $sql .= " and  k99_numpar = $this->k99_numpar";
     }
     if($k99_numpre_n!=null){
       $sql .= " and  k99_numpre_n = $this->k99_numpre_n";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k99_numpre,$this->k99_numpar,$this->k99_numpre_n));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1167,'$this->k99_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,1168,'$this->k99_numpar','A')");
         $resac = db_query("insert into db_acountkey values($acount,1169,'$this->k99_numpre_n','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_numpre"]) || $this->k99_numpre != "")
           $resac = db_query("insert into db_acount values($acount,210,1167,'".AddSlashes(pg_result($resaco,$conresaco,'k99_numpre'))."','$this->k99_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_numpar"]) || $this->k99_numpar != "")
           $resac = db_query("insert into db_acount values($acount,210,1168,'".AddSlashes(pg_result($resaco,$conresaco,'k99_numpar'))."','$this->k99_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_numpre_n"]) || $this->k99_numpre_n != "")
           $resac = db_query("insert into db_acount values($acount,210,1169,'".AddSlashes(pg_result($resaco,$conresaco,'k99_numpre_n'))."','$this->k99_numpre_n',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_codbco"]) || $this->k99_codbco != "")
           $resac = db_query("insert into db_acount values($acount,210,1170,'".AddSlashes(pg_result($resaco,$conresaco,'k99_codbco'))."','$this->k99_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_codage"]) || $this->k99_codage != "")
           $resac = db_query("insert into db_acount values($acount,210,1171,'".AddSlashes(pg_result($resaco,$conresaco,'k99_codage'))."','$this->k99_codage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_numbco"]) || $this->k99_numbco != "")
           $resac = db_query("insert into db_acount values($acount,210,1172,'".AddSlashes(pg_result($resaco,$conresaco,'k99_numbco'))."','$this->k99_numbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_desconto"]) || $this->k99_desconto != "")
           $resac = db_query("insert into db_acount values($acount,210,6140,'".AddSlashes(pg_result($resaco,$conresaco,'k99_desconto'))."','$this->k99_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_tipo"]) || $this->k99_tipo != "")
           $resac = db_query("insert into db_acount values($acount,210,10811,'".AddSlashes(pg_result($resaco,$conresaco,'k99_tipo'))."','$this->k99_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k99_origem"]) || $this->k99_origem != "")
           $resac = db_query("insert into db_acount values($acount,210,10812,'".AddSlashes(pg_result($resaco,$conresaco,'k99_origem'))."','$this->k99_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo Emitidos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recibo Emitidos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k99_numpre."-".$this->k99_numpar."-".$this->k99_numpre_n;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k99_numpre=null,$k99_numpar=null,$k99_numpre_n=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k99_numpre,$k99_numpar,$k99_numpre_n));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1167,'$k99_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,1168,'$k99_numpar','E')");
         $resac = db_query("insert into db_acountkey values($acount,1169,'$k99_numpre_n','E')");
         $resac = db_query("insert into db_acount values($acount,210,1167,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,1168,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,1169,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_numpre_n'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,1170,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,1171,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_codage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,1172,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_numbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,6140,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,10811,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,210,10812,'','".AddSlashes(pg_result($resaco,$iresaco,'k99_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_reciboweb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k99_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k99_numpre = $k99_numpre ";
        }
        if($k99_numpar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k99_numpar = $k99_numpar ";
        }
        if($k99_numpre_n != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k99_numpre_n = $k99_numpre_n ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Recibo Emitidos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k99_numpre."-".$k99_numpar."-".$k99_numpre_n;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Recibo Emitidos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k99_numpre."-".$k99_numpar."-".$k99_numpre_n;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k99_numpre."-".$k99_numpar."-".$k99_numpre_n;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_reciboweb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k99_numpre=null,$k99_numpar=null,$k99_numpre_n=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_reciboweb ";
     $sql2 = "";
     if($dbwhere==""){
       if($k99_numpre!=null ){
         $sql2 .= " where db_reciboweb.k99_numpre = $k99_numpre "; 
       } 
       if($k99_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_reciboweb.k99_numpar = $k99_numpar "; 
       } 
       if($k99_numpre_n!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_reciboweb.k99_numpre_n = $k99_numpre_n "; 
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
   function sql_query_file ( $k99_numpre=null,$k99_numpar=null,$k99_numpre_n=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_reciboweb ";
     $sql2 = "";
     if($dbwhere==""){
       if($k99_numpre!=null ){
         $sql2 .= " where db_reciboweb.k99_numpre = $k99_numpre "; 
       } 
       if($k99_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_reciboweb.k99_numpar = $k99_numpar "; 
       } 
       if($k99_numpre_n!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_reciboweb.k99_numpre_n = $k99_numpre_n "; 
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