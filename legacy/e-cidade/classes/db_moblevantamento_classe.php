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
//CLASSE DA ENTIDADE moblevantamento
class cl_moblevantamento { 
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
   var $j97_sequen = 0; 
   var $j97_codimporta = 0; 
   var $j97_matric = 0; 
   var $j97_endcor = null; 
   var $j97_cidade = null; 
   var $j97_profun = null; 
   var $j97_sitterreno = 0; 
   var $j97_pedol = 0; 
   var $j97_topog = 0; 
   var $j97_vistoria = 0; 
   var $j97_muro = 0; 
   var $j97_calcada = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j97_sequen = int4 = Sequencial 
                 j97_codimporta = int4 = Código Importação 
                 j97_matric = int4 = Matrícula 
                 j97_endcor = varchar(50) = Endereço Corresp. 
                 j97_cidade = varchar(50) = CIdade 
                 j97_profun = varchar(15) = Profundidade 
                 j97_sitterreno = int4 = Situação Terreno 
                 j97_pedol = int4 = Pedologia 
                 j97_topog = int4 = Topografia 
                 j97_vistoria = int4 = Vistoria 
                 j97_muro = int4 = Muro 
                 j97_calcada = int4 = Calçada 
                 ";
   //funcao construtor da classe 
   function cl_moblevantamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("moblevantamento"); 
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
       $this->j97_sequen = ($this->j97_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_sequen"]:$this->j97_sequen);
       $this->j97_codimporta = ($this->j97_codimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_codimporta"]:$this->j97_codimporta);
       $this->j97_matric = ($this->j97_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_matric"]:$this->j97_matric);
       $this->j97_endcor = ($this->j97_endcor == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_endcor"]:$this->j97_endcor);
       $this->j97_cidade = ($this->j97_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_cidade"]:$this->j97_cidade);
       $this->j97_profun = ($this->j97_profun == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_profun"]:$this->j97_profun);
       $this->j97_sitterreno = ($this->j97_sitterreno == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_sitterreno"]:$this->j97_sitterreno);
       $this->j97_pedol = ($this->j97_pedol == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_pedol"]:$this->j97_pedol);
       $this->j97_topog = ($this->j97_topog == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_topog"]:$this->j97_topog);
       $this->j97_vistoria = ($this->j97_vistoria == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_vistoria"]:$this->j97_vistoria);
       $this->j97_muro = ($this->j97_muro == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_muro"]:$this->j97_muro);
       $this->j97_calcada = ($this->j97_calcada == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_calcada"]:$this->j97_calcada);
     }else{
       $this->j97_sequen = ($this->j97_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j97_sequen"]:$this->j97_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($j97_sequen){ 
      $this->atualizacampos();
     if($this->j97_codimporta == null ){ 
       $this->erro_sql = " Campo Código Importação nao Informado.";
       $this->erro_campo = "j97_codimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j97_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_profun == null ){ 
       $this->erro_sql = " Campo Profundidade nao Informado.";
       $this->erro_campo = "j97_profun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_sitterreno == null ){ 
       $this->erro_sql = " Campo Situação Terreno nao Informado.";
       $this->erro_campo = "j97_sitterreno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_pedol == null ){ 
       $this->erro_sql = " Campo Pedologia nao Informado.";
       $this->erro_campo = "j97_pedol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_topog == null ){ 
       $this->erro_sql = " Campo Topografia nao Informado.";
       $this->erro_campo = "j97_topog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_vistoria == null ){ 
       $this->erro_sql = " Campo Vistoria nao Informado.";
       $this->erro_campo = "j97_vistoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_muro == null ){ 
       $this->erro_sql = " Campo Muro nao Informado.";
       $this->erro_campo = "j97_muro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j97_calcada == null ){ 
       $this->erro_sql = " Campo Calçada nao Informado.";
       $this->erro_campo = "j97_calcada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j97_sequen == "" || $j97_sequen == null ){
       $result = @pg_query("select nextval('moblevatamento_j97_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: moblevatamento_j97_sequen_seq do campo: j97_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j97_sequen = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from moblevatamento_j97_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $j97_sequen)){
         $this->erro_sql = " Campo j97_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j97_sequen = $j97_sequen; 
       }
     }
     if(($this->j97_sequen == null) || ($this->j97_sequen == "") ){ 
       $this->erro_sql = " Campo j97_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into moblevantamento(
                                       j97_sequen 
                                      ,j97_codimporta 
                                      ,j97_matric 
                                      ,j97_endcor 
                                      ,j97_cidade 
                                      ,j97_profun 
                                      ,j97_sitterreno 
                                      ,j97_pedol 
                                      ,j97_topog 
                                      ,j97_vistoria 
                                      ,j97_muro 
                                      ,j97_calcada 
                       )
                values (
                                $this->j97_sequen 
                               ,$this->j97_codimporta 
                               ,$this->j97_matric 
                               ,'$this->j97_endcor' 
                               ,'$this->j97_cidade' 
                               ,'$this->j97_profun' 
                               ,$this->j97_sitterreno 
                               ,$this->j97_pedol 
                               ,$this->j97_topog 
                               ,$this->j97_vistoria 
                               ,$this->j97_muro 
                               ,$this->j97_calcada 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Levantamento do cadastro PDA ($this->j97_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Levantamento do cadastro PDA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Levantamento do cadastro PDA ($this->j97_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j97_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j97_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,9737,'$this->j97_sequen','I')");
       $resac = pg_query("insert into db_acount values($acount,1668,9737,'','".AddSlashes(pg_result($resaco,0,'j97_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9733,'','".AddSlashes(pg_result($resaco,0,'j97_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9689,'','".AddSlashes(pg_result($resaco,0,'j97_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9690,'','".AddSlashes(pg_result($resaco,0,'j97_endcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9691,'','".AddSlashes(pg_result($resaco,0,'j97_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9692,'','".AddSlashes(pg_result($resaco,0,'j97_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9693,'','".AddSlashes(pg_result($resaco,0,'j97_sitterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9694,'','".AddSlashes(pg_result($resaco,0,'j97_pedol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9695,'','".AddSlashes(pg_result($resaco,0,'j97_topog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9696,'','".AddSlashes(pg_result($resaco,0,'j97_vistoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9697,'','".AddSlashes(pg_result($resaco,0,'j97_muro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1668,9698,'','".AddSlashes(pg_result($resaco,0,'j97_calcada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j97_sequen=null) { 
      $this->atualizacampos();
     $sql = " update moblevantamento set ";
     $virgula = "";
     if(trim($this->j97_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_sequen"])){ 
       $sql  .= $virgula." j97_sequen = $this->j97_sequen ";
       $virgula = ",";
       if(trim($this->j97_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j97_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_codimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_codimporta"])){ 
       $sql  .= $virgula." j97_codimporta = $this->j97_codimporta ";
       $virgula = ",";
       if(trim($this->j97_codimporta) == null ){ 
         $this->erro_sql = " Campo Código Importação nao Informado.";
         $this->erro_campo = "j97_codimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_matric"])){ 
       $sql  .= $virgula." j97_matric = $this->j97_matric ";
       $virgula = ",";
       if(trim($this->j97_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j97_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_endcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_endcor"])){ 
       $sql  .= $virgula." j97_endcor = '$this->j97_endcor' ";
       $virgula = ",";
     }
     if(trim($this->j97_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_cidade"])){ 
       $sql  .= $virgula." j97_cidade = '$this->j97_cidade' ";
       $virgula = ",";
     }
     if(trim($this->j97_profun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_profun"])){ 
       $sql  .= $virgula." j97_profun = '$this->j97_profun' ";
       $virgula = ",";
       if(trim($this->j97_profun) == null ){ 
         $this->erro_sql = " Campo Profundidade nao Informado.";
         $this->erro_campo = "j97_profun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_sitterreno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_sitterreno"])){ 
       $sql  .= $virgula." j97_sitterreno = $this->j97_sitterreno ";
       $virgula = ",";
       if(trim($this->j97_sitterreno) == null ){ 
         $this->erro_sql = " Campo Situação Terreno nao Informado.";
         $this->erro_campo = "j97_sitterreno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_pedol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_pedol"])){ 
       $sql  .= $virgula." j97_pedol = $this->j97_pedol ";
       $virgula = ",";
       if(trim($this->j97_pedol) == null ){ 
         $this->erro_sql = " Campo Pedologia nao Informado.";
         $this->erro_campo = "j97_pedol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_topog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_topog"])){ 
       $sql  .= $virgula." j97_topog = $this->j97_topog ";
       $virgula = ",";
       if(trim($this->j97_topog) == null ){ 
         $this->erro_sql = " Campo Topografia nao Informado.";
         $this->erro_campo = "j97_topog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_vistoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_vistoria"])){ 
       $sql  .= $virgula." j97_vistoria = $this->j97_vistoria ";
       $virgula = ",";
       if(trim($this->j97_vistoria) == null ){ 
         $this->erro_sql = " Campo Vistoria nao Informado.";
         $this->erro_campo = "j97_vistoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_muro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_muro"])){ 
       $sql  .= $virgula." j97_muro = $this->j97_muro ";
       $virgula = ",";
       if(trim($this->j97_muro) == null ){ 
         $this->erro_sql = " Campo Muro nao Informado.";
         $this->erro_campo = "j97_muro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j97_calcada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j97_calcada"])){ 
       $sql  .= $virgula." j97_calcada = $this->j97_calcada ";
       $virgula = ",";
       if(trim($this->j97_calcada) == null ){ 
         $this->erro_sql = " Campo Calçada nao Informado.";
         $this->erro_campo = "j97_calcada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j97_sequen!=null){
       $sql .= " j97_sequen = $this->j97_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j97_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9737,'$this->j97_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_sequen"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9737,'".AddSlashes(pg_result($resaco,$conresaco,'j97_sequen'))."','$this->j97_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_codimporta"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9733,'".AddSlashes(pg_result($resaco,$conresaco,'j97_codimporta'))."','$this->j97_codimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_matric"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9689,'".AddSlashes(pg_result($resaco,$conresaco,'j97_matric'))."','$this->j97_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_endcor"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9690,'".AddSlashes(pg_result($resaco,$conresaco,'j97_endcor'))."','$this->j97_endcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_cidade"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9691,'".AddSlashes(pg_result($resaco,$conresaco,'j97_cidade'))."','$this->j97_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_profun"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9692,'".AddSlashes(pg_result($resaco,$conresaco,'j97_profun'))."','$this->j97_profun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_sitterreno"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9693,'".AddSlashes(pg_result($resaco,$conresaco,'j97_sitterreno'))."','$this->j97_sitterreno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_pedol"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9694,'".AddSlashes(pg_result($resaco,$conresaco,'j97_pedol'))."','$this->j97_pedol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_topog"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9695,'".AddSlashes(pg_result($resaco,$conresaco,'j97_topog'))."','$this->j97_topog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_vistoria"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9696,'".AddSlashes(pg_result($resaco,$conresaco,'j97_vistoria'))."','$this->j97_vistoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_muro"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9697,'".AddSlashes(pg_result($resaco,$conresaco,'j97_muro'))."','$this->j97_muro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j97_calcada"]))
           $resac = pg_query("insert into db_acount values($acount,1668,9698,'".AddSlashes(pg_result($resaco,$conresaco,'j97_calcada'))."','$this->j97_calcada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento do cadastro PDA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j97_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento do cadastro PDA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j97_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j97_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j97_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j97_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9737,'$j97_sequen','E')");
         $resac = pg_query("insert into db_acount values($acount,1668,9737,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9733,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9689,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9690,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_endcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9691,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9692,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9693,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_sitterreno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9694,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_pedol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9695,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_topog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9696,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_vistoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9697,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_muro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1668,9698,'','".AddSlashes(pg_result($resaco,$iresaco,'j97_calcada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from moblevantamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j97_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j97_sequen = $j97_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Levantamento do cadastro PDA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j97_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Levantamento do cadastro PDA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j97_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j97_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:moblevantamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j97_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamento ";
     $sql .= "      inner join mobimportacao  on  mobimportacao.j95_codimporta = moblevantamento.j97_codimporta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mobimportacao.j95_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j97_sequen!=null ){
         $sql2 .= " where moblevantamento.j97_sequen = $j97_sequen "; 
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
   function sql_query_file ( $j97_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($j97_sequen!=null ){
         $sql2 .= " where moblevantamento.j97_sequen = $j97_sequen "; 
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