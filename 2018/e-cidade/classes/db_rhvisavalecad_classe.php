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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhvisavalecad
class cl_rhvisavalecad { 
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
   var $rh49_codigo = 0; 
   var $rh49_anousu = 0; 
   var $rh49_mesusu = 0; 
   var $rh49_numcgm = 0; 
   var $rh49_regist = 0; 
   var $rh49_valor = 0; 
   var $rh49_instit = 0; 
   var $rh49_perc = 0; 
   var $rh49_diasafasta = 0; 
   var $rh49_valormes = 0; 
   var $rh49_percdep = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh49_codigo = int8 = Código 
                 rh49_anousu = int4 = Ano de competência 
                 rh49_mesusu = int4 = Mês de competência 
                 rh49_numcgm = int4 = Numcgm 
                 rh49_regist = int4 = Matrícula do Servidor 
                 rh49_valor = float8 = Valor 
                 rh49_instit = int4 = codigo da instituicao 
                 rh49_perc = float4 = Perc. Desconto 
                 rh49_diasafasta = int4 = Dias afastados 
                 rh49_valormes = float4 = Valor mês 
                 rh49_percdep = int4 = Perc. Depósito 
                 ";
   //funcao construtor da classe 
   function cl_rhvisavalecad() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhvisavalecad"); 
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
       $this->rh49_codigo = ($this->rh49_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_codigo"]:$this->rh49_codigo);
       $this->rh49_anousu = ($this->rh49_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_anousu"]:$this->rh49_anousu);
       $this->rh49_mesusu = ($this->rh49_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_mesusu"]:$this->rh49_mesusu);
       $this->rh49_numcgm = ($this->rh49_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_numcgm"]:$this->rh49_numcgm);
       $this->rh49_regist = ($this->rh49_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_regist"]:$this->rh49_regist);
       $this->rh49_valor = ($this->rh49_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_valor"]:$this->rh49_valor);
       $this->rh49_instit = ($this->rh49_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_instit"]:$this->rh49_instit);
       $this->rh49_perc = ($this->rh49_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_perc"]:$this->rh49_perc);
       $this->rh49_diasafasta = ($this->rh49_diasafasta == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_diasafasta"]:$this->rh49_diasafasta);
       $this->rh49_valormes = ($this->rh49_valormes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_valormes"]:$this->rh49_valormes);
       $this->rh49_percdep = ($this->rh49_percdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_percdep"]:$this->rh49_percdep);
     }else{
       $this->rh49_codigo = ($this->rh49_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh49_codigo"]:$this->rh49_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh49_codigo){ 
      $this->atualizacampos();
     if($this->rh49_anousu == null ){ 
       $this->erro_sql = " Campo Ano de competência nao Informado.";
       $this->erro_campo = "rh49_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_mesusu == null ){ 
       $this->erro_sql = " Campo Mês de competência nao Informado.";
       $this->erro_campo = "rh49_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "rh49_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_regist == null ){ 
       $this->erro_sql = " Campo Matrícula do Servidor nao Informado.";
       $this->erro_campo = "rh49_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh49_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "rh49_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_perc == null ){ 
       $this->erro_sql = " Campo Perc. Desconto nao Informado.";
       $this->erro_campo = "rh49_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh49_diasafasta == null ){ 
       $this->rh49_diasafasta = "0";
     }
     if($this->rh49_valormes == null ){ 
       $this->rh49_valormes = "0";
     }
     if($this->rh49_percdep == null ){ 
       $this->erro_sql = " Campo Perc. Depósito nao Informado.";
       $this->erro_campo = "rh49_percdep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh49_codigo == "" || $rh49_codigo == null ){
       $result = db_query("select nextval('rhvisavalecad_rh49_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhvisavalecad_rh49_codigo_seq do campo: rh49_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh49_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhvisavalecad_rh49_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh49_codigo)){
         $this->erro_sql = " Campo rh49_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh49_codigo = $rh49_codigo; 
       }
     }
     if(($this->rh49_codigo == null) || ($this->rh49_codigo == "") ){ 
       $this->erro_sql = " Campo rh49_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhvisavalecad(
                                       rh49_codigo 
                                      ,rh49_anousu 
                                      ,rh49_mesusu 
                                      ,rh49_numcgm 
                                      ,rh49_regist 
                                      ,rh49_valor 
                                      ,rh49_instit 
                                      ,rh49_perc 
                                      ,rh49_diasafasta 
                                      ,rh49_valormes 
                                      ,rh49_percdep 
                       )
                values (
                                $this->rh49_codigo 
                               ,$this->rh49_anousu 
                               ,$this->rh49_mesusu 
                               ,$this->rh49_numcgm 
                               ,$this->rh49_regist 
                               ,$this->rh49_valor 
                               ,$this->rh49_instit 
                               ,$this->rh49_perc 
                               ,$this->rh49_diasafasta 
                               ,$this->rh49_valormes 
                               ,$this->rh49_percdep 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos funcionários ($this->rh49_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos funcionários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos funcionários ($this->rh49_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh49_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh49_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8702,'$this->rh49_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1485,8702,'','".AddSlashes(pg_result($resaco,0,'rh49_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8703,'','".AddSlashes(pg_result($resaco,0,'rh49_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8704,'','".AddSlashes(pg_result($resaco,0,'rh49_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8705,'','".AddSlashes(pg_result($resaco,0,'rh49_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8706,'','".AddSlashes(pg_result($resaco,0,'rh49_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8707,'','".AddSlashes(pg_result($resaco,0,'rh49_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,8708,'','".AddSlashes(pg_result($resaco,0,'rh49_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,12503,'','".AddSlashes(pg_result($resaco,0,'rh49_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,12504,'','".AddSlashes(pg_result($resaco,0,'rh49_diasafasta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,12505,'','".AddSlashes(pg_result($resaco,0,'rh49_valormes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1485,15190,'','".AddSlashes(pg_result($resaco,0,'rh49_percdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh49_codigo=null) { 
      $this->atualizacampos();
     $sql = " update rhvisavalecad set ";
     $virgula = "";
     if(trim($this->rh49_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_codigo"])){ 
       $sql  .= $virgula." rh49_codigo = $this->rh49_codigo ";
       $virgula = ",";
       if(trim($this->rh49_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "rh49_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_anousu"])){ 
       $sql  .= $virgula." rh49_anousu = $this->rh49_anousu ";
       $virgula = ",";
       if(trim($this->rh49_anousu) == null ){ 
         $this->erro_sql = " Campo Ano de competência nao Informado.";
         $this->erro_campo = "rh49_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_mesusu"])){ 
       $sql  .= $virgula." rh49_mesusu = $this->rh49_mesusu ";
       $virgula = ",";
       if(trim($this->rh49_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês de competência nao Informado.";
         $this->erro_campo = "rh49_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_numcgm"])){ 
       $sql  .= $virgula." rh49_numcgm = $this->rh49_numcgm ";
       $virgula = ",";
       if(trim($this->rh49_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "rh49_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_regist"])){ 
       $sql  .= $virgula." rh49_regist = $this->rh49_regist ";
       $virgula = ",";
       if(trim($this->rh49_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula do Servidor nao Informado.";
         $this->erro_campo = "rh49_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_valor"])){ 
       $sql  .= $virgula." rh49_valor = $this->rh49_valor ";
       $virgula = ",";
       if(trim($this->rh49_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh49_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_instit"])){ 
       $sql  .= $virgula." rh49_instit = $this->rh49_instit ";
       $virgula = ",";
       if(trim($this->rh49_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "rh49_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_perc"])){ 
       $sql  .= $virgula." rh49_perc = $this->rh49_perc ";
       $virgula = ",";
       if(trim($this->rh49_perc) == null ){ 
         $this->erro_sql = " Campo Perc. Desconto nao Informado.";
         $this->erro_campo = "rh49_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh49_diasafasta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_diasafasta"])){ 
        if(trim($this->rh49_diasafasta)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh49_diasafasta"])){ 
           $this->rh49_diasafasta = "0" ; 
        } 
       $sql  .= $virgula." rh49_diasafasta = $this->rh49_diasafasta ";
       $virgula = ",";
     }
     if(trim($this->rh49_valormes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_valormes"])){ 
        if(trim($this->rh49_valormes)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh49_valormes"])){ 
           $this->rh49_valormes = "0" ; 
        } 
       $sql  .= $virgula." rh49_valormes = $this->rh49_valormes ";
       $virgula = ",";
     }
     if(trim($this->rh49_percdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh49_percdep"])){ 
       $sql  .= $virgula." rh49_percdep = $this->rh49_percdep ";
       $virgula = ",";
       if(trim($this->rh49_percdep) == null ){ 
         $this->erro_sql = " Campo Perc. Depósito nao Informado.";
         $this->erro_campo = "rh49_percdep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh49_codigo!=null){
       $sql .= " rh49_codigo = $this->rh49_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh49_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8702,'$this->rh49_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_codigo"]) || $this->rh49_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1485,8702,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_codigo'))."','$this->rh49_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_anousu"]) || $this->rh49_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1485,8703,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_anousu'))."','$this->rh49_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_mesusu"]) || $this->rh49_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,1485,8704,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_mesusu'))."','$this->rh49_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_numcgm"]) || $this->rh49_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1485,8705,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_numcgm'))."','$this->rh49_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_regist"]) || $this->rh49_regist != "")
           $resac = db_query("insert into db_acount values($acount,1485,8706,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_regist'))."','$this->rh49_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_valor"]) || $this->rh49_valor != "")
           $resac = db_query("insert into db_acount values($acount,1485,8707,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_valor'))."','$this->rh49_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_instit"]) || $this->rh49_instit != "")
           $resac = db_query("insert into db_acount values($acount,1485,8708,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_instit'))."','$this->rh49_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_perc"]) || $this->rh49_perc != "")
           $resac = db_query("insert into db_acount values($acount,1485,12503,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_perc'))."','$this->rh49_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_diasafasta"]) || $this->rh49_diasafasta != "")
           $resac = db_query("insert into db_acount values($acount,1485,12504,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_diasafasta'))."','$this->rh49_diasafasta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_valormes"]) || $this->rh49_valormes != "")
           $resac = db_query("insert into db_acount values($acount,1485,12505,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_valormes'))."','$this->rh49_valormes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh49_percdep"]) || $this->rh49_percdep != "")
           $resac = db_query("insert into db_acount values($acount,1485,15190,'".AddSlashes(pg_result($resaco,$conresaco,'rh49_percdep'))."','$this->rh49_percdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos funcionários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh49_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos funcionários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh49_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh49_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh49_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh49_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8702,'$rh49_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1485,8702,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8703,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8704,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8705,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8706,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8707,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,8708,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,12503,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,12504,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_diasafasta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,12505,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_valormes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1485,15190,'','".AddSlashes(pg_result($resaco,$iresaco,'rh49_percdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhvisavalecad
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh49_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh49_codigo = $rh49_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos funcionários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh49_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos funcionários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh49_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh49_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhvisavalecad";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh49_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   	//copiado da classe antiga
    $sql .= " from rhvisavalecad 																																				       ";
    $sql .= "      inner join cgm           on cgm.z01_numcgm 					  = rhvisavalecad.rh49_numcgm					 ";
    $sql .= "      inner join db_config     on db_config.codigo 					= rhvisavalecad.rh49_instit					 ";
    $sql .= "      inner join rhpessoal     on rhpessoal.rh01_regist 		  = rhvisavalecad.rh49_regist				   ";
    $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_anousu   =  " . db_anofolha() . "             ";
    $sql .= "                              and rhpessoalmov.rh02_mesusu   =  " . db_mesfolha() . "             ";
    $sql .= "                              and rhpessoalmov.rh02_regist   =  rhpessoal.rh01_regist             ";
    $sql .= "                              and rhpessoalmov.rh02_instit   =  " . db_getsession('DB_instit') . "";
    $sql .= "      inner join rhlota        on rhlota.r70_codigo          = rhpessoalmov.rh02_lota 						 ";
    $sql .= "      inner join rhfuncao      on rhfuncao.rh37_funcao       = rhpessoal.rh01_funcao              ";
    $sql .= "                              and rhfuncao.rh37_instit       = rhpessoalmov.rh02_instit 					 ";

    $sql2 = "";
    if($dbwhere==""){
    	if($rh49_codigo!=null ){
    		$sql2 .= " where rhvisavalecad.rh49_codigo = $rh49_codigo ";
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
   function sql_query_file ( $rh49_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavalecad ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh49_codigo!=null ){
         $sql2 .= " where rhvisavalecad.rh49_codigo = $rh49_codigo "; 
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
   function sql_query_documentos ( $rh49_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavalecad ";
     $sql .= "      inner  join  rhpessoal      on  rhpessoal.rh01_regist      =  rhvisavalecad.rh49_regist
                                               and  rhpessoal.rh01_numcgm      =  rhvisavalecad.rh49_numcgm";
     $sql .= "      inner  join  cgm            on  cgm.z01_numcgm             =  rhpessoal.rh01_numcgm";
     $sql .= "      inner  join  rhpessoalmov   on  rhpessoalmov.rh02_anousu   =  ".db_anofolha()."
                                               and  rhpessoalmov.rh02_mesusu   =  ".db_mesfolha()."
                                               and  rhpessoalmov.rh02_regist   =  rhpessoal.rh01_regist";
     $sql .= "      left   join  rhpesdoc       on  rhpesdoc.rh16_regist       =  rhpessoal.rh01_regist";
     $sql .= "      left   join  rhpesrescisao  on  rhpesrescisao.rh05_seqpes  =  rhpessoalmov.rh02_seqpes";
     $sql2 = "";
     if($dbwhere==""){
       if($rh49_codigo!=null ){
         $sql2 .= " where rhvisavalecad.rh49_codigo = $rh49_codigo "; 
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
   function sql_query_lotaexe ( $rh49_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhvisavalecad ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhvisavalecad.rh49_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhvisavalecad.rh49_instit";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhvisavalecad.rh49_regist";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_anousu = rhvisavalecad.rh49_anousu
                                            and rhpessoalmov.rh02_mesusu = rhvisavalecad.rh49_mesusu
                                            and rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                            and rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhpessoalmov.rh02_lota and r70_instit = ".db_getsession("DB_instit")."  ";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhlotaexe  on  rhlotaexe.rh26_anousu = rhpessoalmov.rh02_anousu
                                         and  rhlotaexe.rh26_codigo = rhlota.r70_codigo";
     $sql .= "      left  join orcorgao   on  orcorgao.o40_anousu   = rhlotaexe.rh26_anousu
                                         and  orcorgao.o40_orgao    = rhlotaexe.rh26_orgao";
     $sql .= "      left  join orcunidade on  orcunidade.o41_anousu   = rhlotaexe.rh26_anousu
                                         and  orcunidade.o41_orgao    = rhlotaexe.rh26_orgao
					 and  orcunidade.o41_unidade  = rhlotaexe.rh26_unidade";
     $sql2 = "";
     if($dbwhere==""){
       if($rh49_codigo!=null ){
         $sql2 .= " where rhvisavalecad.rh49_codigo = $rh49_codigo "; 
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