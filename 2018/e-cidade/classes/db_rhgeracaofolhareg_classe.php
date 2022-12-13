<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhgeracaofolhareg
class cl_rhgeracaofolhareg { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $rh104_sequencial = 0; 
   var $rh104_seqpes = 0; 
   var $rh104_instit = 0; 
   var $rh104_rhgeracaofolha = 0; 
   var $rh104_vlrsalario = 0; 
   var $rh104_vlrliquido = 0; 
   var $rh104_vlrprovento = 0; 
   var $rh104_vlrdesconto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh104_sequencial     = int4   = Sequencial 
                 rh104_seqpes         = int4   = Movimentação 
                 rh104_instit         = int4   = Instituicao 
                 rh104_rhgeracaofolha = int4   = Código da Geração 
                 rh104_vlrsalario     = float4 = Vlr. Salário 
                 rh104_vlrliquido     = float4 = Valor Líquido 
                 rh104_vlrprovento    = float4 = Valor Provento 
                 rh104_vlrdesconto    = float4 = Valor Desconto 
                 ";
   //funcao construtor da classe 
   function cl_rhgeracaofolhareg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhgeracaofolhareg"); 
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
       $this->rh104_sequencial = ($this->rh104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_sequencial"]:$this->rh104_sequencial);
       $this->rh104_seqpes = ($this->rh104_seqpes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_seqpes"]:$this->rh104_seqpes);
       $this->rh104_instit = ($this->rh104_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_instit"]:$this->rh104_instit);
       $this->rh104_rhgeracaofolha = ($this->rh104_rhgeracaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_rhgeracaofolha"]:$this->rh104_rhgeracaofolha);
       $this->rh104_vlrsalario = ($this->rh104_vlrsalario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_vlrsalario"]:$this->rh104_vlrsalario);
       $this->rh104_vlrliquido = ($this->rh104_vlrliquido == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_vlrliquido"]:$this->rh104_vlrliquido);
       $this->rh104_vlrprovento = ($this->rh104_vlrprovento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_vlrprovento"]:$this->rh104_vlrprovento);
       $this->rh104_vlrdesconto = ($this->rh104_vlrdesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_vlrdesconto"]:$this->rh104_vlrdesconto);
     }else{
       $this->rh104_sequencial = ($this->rh104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh104_sequencial"]:$this->rh104_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh104_sequencial){ 
      $this->atualizacampos();
     if($this->rh104_seqpes == null ){ 
       $this->erro_sql = " Campo Movimentação nao Informado.";
       $this->erro_campo = "rh104_seqpes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_instit == null ){ 
       $this->erro_sql = " Campo Instituicao nao Informado.";
       $this->erro_campo = "rh104_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_rhgeracaofolha == null ){ 
       $this->erro_sql = " Campo Código da Geração nao Informado.";
       $this->erro_campo = "rh104_rhgeracaofolha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_vlrsalario == null ){ 
       $this->erro_sql = " Campo Vlr. Salário nao Informado.";
       $this->erro_campo = "rh104_vlrsalario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_vlrliquido == null ){ 
       $this->erro_sql = " Campo Valor Líquido nao Informado.";
       $this->erro_campo = "rh104_vlrliquido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_vlrprovento == null ){ 
       $this->erro_sql = " Campo Valor Provento nao Informado.";
       $this->erro_campo = "rh104_vlrprovento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh104_vlrdesconto == null ){ 
       $this->erro_sql = " Campo Valor Desconto nao Informado.";
       $this->erro_campo = "rh104_vlrdesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh104_sequencial == "" || $rh104_sequencial == null ){
       $result = @pg_query("select nextval('rhgeracaofolhareg_rh104_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhgeracaofolhareg_rh104_sequencial_seq do campo: rh104_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh104_sequencial = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from rhgeracaofolhareg_rh104_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh104_sequencial)){
         $this->erro_sql = " Campo rh104_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh104_sequencial = $rh104_sequencial; 
       }
     }
     if(($this->rh104_sequencial == null) || ($this->rh104_sequencial == "") ){ 
       $this->erro_sql = " Campo rh104_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into rhgeracaofolhareg(
                                       rh104_sequencial 
                                      ,rh104_seqpes 
                                      ,rh104_instit 
                                      ,rh104_rhgeracaofolha 
                                      ,rh104_vlrsalario 
                                      ,rh104_vlrliquido 
                                      ,rh104_vlrprovento 
                                      ,rh104_vlrdesconto 
                       )
                values (
                                $this->rh104_sequencial 
                               ,$this->rh104_seqpes 
                               ,$this->rh104_instit 
                               ,$this->rh104_rhgeracaofolha 
                               ,$this->rh104_vlrsalario 
                               ,$this->rh104_vlrliquido 
                               ,$this->rh104_vlrprovento 
                               ,$this->rh104_vlrdesconto 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhgeracaofolhareg ($this->rh104_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhgeracaofolhareg já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhgeracaofolhareg ($this->rh104_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rh104_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18108,'$this->rh104_sequencial','I')");
       $resac = pg_query("insert into db_acount values($acount,3198,18108,'','".pg_result($resaco,0,'rh104_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18109,'','".pg_result($resaco,0,'rh104_seqpes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18115,'','".pg_result($resaco,0,'rh104_instit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18110,'','".pg_result($resaco,0,'rh104_rhgeracaofolha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18111,'','".pg_result($resaco,0,'rh104_vlrsalario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18112,'','".pg_result($resaco,0,'rh104_vlrliquido')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18113,'','".pg_result($resaco,0,'rh104_vlrprovento')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18114,'','".pg_result($resaco,0,'rh104_vlrdesconto')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh104_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhgeracaofolhareg set ";
     $virgula = "";
     if(trim($this->rh104_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_sequencial"])){ 
        if(trim($this->rh104_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_sequencial"])){ 
           $this->rh104_sequencial = "0" ; 
        } 
       $sql  .= $virgula." rh104_sequencial = $this->rh104_sequencial ";
       $virgula = ",";
       if(trim($this->rh104_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh104_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_seqpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_seqpes"])){ 
        if(trim($this->rh104_seqpes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_seqpes"])){ 
           $this->rh104_seqpes = "0" ; 
        } 
       $sql  .= $virgula." rh104_seqpes = $this->rh104_seqpes ";
       $virgula = ",";
       if(trim($this->rh104_seqpes) == null ){ 
         $this->erro_sql = " Campo Movimentação nao Informado.";
         $this->erro_campo = "rh104_seqpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_instit"])){ 
        if(trim($this->rh104_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_instit"])){ 
           $this->rh104_instit = "0" ; 
        } 
       $sql  .= $virgula." rh104_instit = $this->rh104_instit ";
       $virgula = ",";
       if(trim($this->rh104_instit) == null ){ 
         $this->erro_sql = " Campo Instituicao nao Informado.";
         $this->erro_campo = "rh104_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_rhgeracaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_rhgeracaofolha"])){ 
        if(trim($this->rh104_rhgeracaofolha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_rhgeracaofolha"])){ 
           $this->rh104_rhgeracaofolha = "0" ; 
        } 
       $sql  .= $virgula." rh104_rhgeracaofolha = $this->rh104_rhgeracaofolha ";
       $virgula = ",";
       if(trim($this->rh104_rhgeracaofolha) == null ){ 
         $this->erro_sql = " Campo Código da Geração nao Informado.";
         $this->erro_campo = "rh104_rhgeracaofolha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_vlrsalario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrsalario"])){ 
        if(trim($this->rh104_vlrsalario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrsalario"])){ 
           $this->rh104_vlrsalario = "0" ; 
        } 
       $sql  .= $virgula." rh104_vlrsalario = $this->rh104_vlrsalario ";
       $virgula = ",";
       if(trim($this->rh104_vlrsalario) == null ){ 
         $this->erro_sql = " Campo Vlr. Salário nao Informado.";
         $this->erro_campo = "rh104_vlrsalario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_vlrliquido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrliquido"])){ 
        if(trim($this->rh104_vlrliquido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrliquido"])){ 
           $this->rh104_vlrliquido = "0" ; 
        } 
       $sql  .= $virgula." rh104_vlrliquido = $this->rh104_vlrliquido ";
       $virgula = ",";
       if(trim($this->rh104_vlrliquido) == null ){ 
         $this->erro_sql = " Campo Valor Líquido nao Informado.";
         $this->erro_campo = "rh104_vlrliquido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_vlrprovento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrprovento"])){ 
        if(trim($this->rh104_vlrprovento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrprovento"])){ 
           $this->rh104_vlrprovento = "0" ; 
        } 
       $sql  .= $virgula." rh104_vlrprovento = $this->rh104_vlrprovento ";
       $virgula = ",";
       if(trim($this->rh104_vlrprovento) == null ){ 
         $this->erro_sql = " Campo Valor Provento nao Informado.";
         $this->erro_campo = "rh104_vlrprovento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh104_vlrdesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrdesconto"])){ 
        if(trim($this->rh104_vlrdesconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrdesconto"])){ 
           $this->rh104_vlrdesconto = "0" ; 
        } 
       $sql  .= $virgula." rh104_vlrdesconto = $this->rh104_vlrdesconto ";
       $virgula = ",";
       if(trim($this->rh104_vlrdesconto) == null ){ 
         $this->erro_sql = " Campo Valor Desconto nao Informado.";
         $this->erro_campo = "rh104_vlrdesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  rh104_sequencial = $this->rh104_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->rh104_sequencial));
     if($this->numrows>0){       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18108,'$this->rh104_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_sequencial"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18108,'".pg_result($resaco,0,'rh104_sequencial')."','$this->rh104_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_seqpes"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18109,'".pg_result($resaco,0,'rh104_seqpes')."','$this->rh104_seqpes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_instit"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18115,'".pg_result($resaco,0,'rh104_instit')."','$this->rh104_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_rhgeracaofolha"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18110,'".pg_result($resaco,0,'rh104_rhgeracaofolha')."','$this->rh104_rhgeracaofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrsalario"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18111,'".pg_result($resaco,0,'rh104_vlrsalario')."','$this->rh104_vlrsalario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrliquido"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18112,'".pg_result($resaco,0,'rh104_vlrliquido')."','$this->rh104_vlrliquido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrprovento"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18113,'".pg_result($resaco,0,'rh104_vlrprovento')."','$this->rh104_vlrprovento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh104_vlrdesconto"]))
         $resac = pg_query("insert into db_acount values($acount,3198,18114,'".pg_result($resaco,0,'rh104_vlrdesconto')."','$this->rh104_vlrdesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhareg nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhareg nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh104_sequencial=null) { 
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->rh104_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,18108,'$this->rh104_sequencial','E')");
       $resac = pg_query("insert into db_acount values($acount,3198,18108,'','".pg_result($resaco,0,'rh104_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18109,'','".pg_result($resaco,0,'rh104_seqpes')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18115,'','".pg_result($resaco,0,'rh104_instit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18110,'','".pg_result($resaco,0,'rh104_rhgeracaofolha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18111,'','".pg_result($resaco,0,'rh104_vlrsalario')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18112,'','".pg_result($resaco,0,'rh104_vlrliquido')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18113,'','".pg_result($resaco,0,'rh104_vlrprovento')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,3198,18114,'','".pg_result($resaco,0,'rh104_vlrdesconto')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from rhgeracaofolhareg
                    where ";
     $sql2 = "";
      if($this->rh104_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " rh104_sequencial = $this->rh104_sequencial ";
}
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhareg nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhareg nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhareg ";
     $sql .= "      inner join rhpessoalmov  on  rhpessoalmov.rh02_seqpes = rhgeracaofolhareg.rh104_seqpes and  rhpessoalmov.rh02_instit = rhgeracaofolhareg.rh104_instit";
     $sql .= "      inner join rhgeracaofolha  on  rhgeracaofolha.rh102_sequencial = rhgeracaofolhareg.rh104_rhgeracaofolha";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoalmov.rh02_instit";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota";
     $sql .= "      inner join rhregime  on  rhregime.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      inner join rhtipoapos  on  rhtipoapos.rh88_sequencial = rhpessoalmov.rh02_rhtipoapos";
     $sql .= "      inner join db_config  as a on   a.codigo = rhpessoalmov.rh02_instit";
     $sql .= "      inner join rhlota  as b on   b.r70_codigo = rhpessoalmov.rh02_lota";
     $sql .= "      inner join rhregime  as c on   c.rh30_codreg = rhpessoalmov.rh02_codreg";
     $sql .= "      inner join rhtipoapos  as d on   d.rh88_sequencial = rhpessoalmov.rh02_rhtipoapos";
     $sql .= "      inner join db_config  as d on   d.codigo = rhgeracaofolha.rh102_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhgeracaofolha.rh102_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh104_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhareg.rh104_sequencial = $rh104_sequencial "; 
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
   function sql_query_file ( $rh104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhgeracaofolhareg ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh104_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhareg.rh104_sequencial = $rh104_sequencial "; 
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